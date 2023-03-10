<?php

namespace App\Controller\Recruiting;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use App\Entity\VacancyViewerUser;
use App\Form\Recruiting\CandidateStepCvReceivedType;
use App\Form\Recruiting\CandidateStepCvReceivedTypeForHunting;
use App\Form\Recruiting\CandidateStepCvReceivedTypeForRecommendation;
use App\Form\Recruiting\RecruiterType;
use App\Form\Recruiting\VacancyType;
use App\Form\Recruiting\VacancyTypeDenied;
use App\Form\Recruiting\ViewerType;
use App\Repository\CandidateRepository;
use App\Repository\UserRepository;
use App\Repository\VacancyRepository;
use App\Service\CandidateForms\CandidateForms;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\ContextForRelationStrategyLinks;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators\CandidateLinkCheckExistence;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\StrategyExistenceLinks;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\StrategyNonExistenceLinks;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\ExistsCandidateBuilderLinks;
use App\Service\Vacancy\CandidateVacancyAmount\CandidateVacancyAmountCheckLogic;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\ContextForRelationStrategy;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators\CandidateVacancyCheckExistence;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\StrategyExistence;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\StrategyNonExistence;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\ExistsCandidateBuilder;
use App\Service\Vacancy\Display\ListEntry\ExpiredTimeCalculator;
use App\Service\Vacancy\Display\ListEntry\VacancyListEntryDTOBuilder;
use App\Service\Vacancy\Letters\CreateForHrManager\NewVacancyMessageBuilderForHrManager;
use App\Service\Vacancy\Letters\CreateForManager\NewVacancyMessageBuilderForManager;
use App\Service\Vacancy\Letters\CreateVacancy\NewVacancyMessageBuilder;
use App\Service\Vacancy\VacancyTimeDecorator\VacancyTimeDecorator;
use DateTime;
use DateTimeImmutable;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @IsGranted("ROLE_VACANCY_VIEWER_USER")
 * @Route("/vacancy")
 */
class VacancyController extends AbstractController
{

    /**
     * @var Environment
     */
    private $environment;

    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const CANDIDATE = 'candidate';

    public const CANDIDATE_NAME = 'name';

    public const CANDIDATE_SURNAME = 'surname';

    public const VACANCY_EXPIRED_TIME = 'expiredTime';

    public const CANDIDATE_EDIT = 'candidate_edit';

    public const CONSTRAINT = 'constraints';

    public const CANDIDATE_VACANCY = 'candidateVacancy';

    public const CANDIDATE_LINK = 'candidateLink';

    public const RECEIVED_CV = 'receivedCv';

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @Route("/", name="vacancy_index", methods={"GET"})
     * @param VacancyRepository $vacancyRepository
     * @param VacancyListEntryDTOBuilder $builder
     * @return Response
     * @throws NoDateException
     */
    public function index(VacancyRepository $vacancyRepository, VacancyListEntryDTOBuilder $builder): Response
    {
        $vacancies = [];

        $assignee = $this->getUser()->getRoles();
        $currentUser = $this->getUser()->getId();
        if (in_array('ROLE_RECRUITER', $assignee, true)) {
            foreach ($vacancyRepository->findBy([
                'assignee' => $currentUser
            ]) as $vacancy) {
                $vacancies[] = $builder->build($vacancy);
            }

        } else {
            foreach ($vacancyRepository->findAll() as $vacancy) {
                $vacancies[] = $builder->build($vacancy);
            }
        }

        return $this->render('recruiting/vacancy/index.html.twig', [
            'vacancies' => $vacancies
        ]);
    }


    /**
     * @IsGranted("ROLE_TOP_MANAGER")
     * @Route("/approve/{id}", name="approved", methods={"GET"})
     * @param UserRepository $userRepository
     * @param Vacancy $vacancy
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws NoDateException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function approve(UserRepository $userRepository, Vacancy $vacancy, Swift_Mailer $mailer): Response
    {

        if ($vacancy->getStatus() !== null) {
            throw new NotFoundHttpException('This request was already approved or denied');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $vacancy->setStatus('Approved');
        $vacancy->setApproveDate(new DateTimeImmutable('now'));
        $vacancy->setApprovedBy($this->getUser());
        $entityManager->persist($vacancy);

        $messageBuilder = new NewVacancyMessageBuilderForManager(
            $vacancy, $this->environment
        );

        $messageBuilderHr = new NewVacancyMessageBuilderForHrManager(
            $userRepository, $vacancy, $this->environment
        );
        $entityManager->flush();
        $mailer->send($messageBuilder->build());
        $mailer->send($messageBuilderHr->build());

        return $this->render('recruiting/vacancy/approved.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy
        ]);
    }

    /**
     * @IsGranted("ROLE_TOP_MANAGER")
     * @Route("/deny/{id}", name="denied", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @return Response
     */
    public function deny(Vacancy $vacancy, Request $request): Response
    {
        if ($vacancy->getStatus() !== null) {
            throw new NotFoundHttpException('This request was already approved or denied');
        }

        $form = $this->createForm(VacancyTypeDenied::class, $vacancy);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $vacancy->setStatus('Denied');
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('vacancy_denied', [
                'id' => $vacancy->getId(),
            ]);
        }
        return $this->render('recruiting/vacancy/denied.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/result", name="vacancy_result", methods={"GET"})
     */
    public function result(): Response
    {
        return $this->render('recruiting/vacancy/result.html.twig', [
            'controller_name' => 'ResultController',
        ]);
    }

    /**
     * @Route("/denied/{id}", name="vacancy_denied", methods={"GET"})
     * @param Vacancy $vacancy
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws NoDateException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function resultDenied(Vacancy $vacancy, Swift_Mailer $mailer): Response
    {
        $messageBuilder = new NewVacancyMessageBuilderForManager(
            $vacancy, $this->environment
        );
        $mailer->send($messageBuilder->build());

        return $this->render('recruiting/vacancy/deniedResult.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy
        ]);
    }

    /**
     * @Route("/new", name="vacancy_new", methods={"GET","POST"})
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws NoDateException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function new(Request $request, Swift_Mailer $mailer): Response
    {

        $vacancy = new Vacancy();
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);
        $vacancy->setCreatedBy($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $vacancy->setCreatedAt(new DateTimeImmutable('now'));
            $entityManager->persist($vacancy);
            $entityManager->flush();

            $messageBuilder = new NewVacancyMessageBuilder(
                $vacancy, $this->environment
            );
            $mailer->send($messageBuilder->build());

            return $this->redirectToRoute('vacancy_result');
        }


        return $this->render('recruiting/vacancy/new.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vacancy_show", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param ExpiredTimeCalculator $timeCalculator
     * @return Response
     * @throws Exception
     */
    public function show(Vacancy $vacancy, Request $request, ExpiredTimeCalculator $timeCalculator): Response
    {
        $viewerUser = new VacancyViewerUser();
        $vacancyTimeDecorator = new VacancyTimeDecorator($vacancy);
        $formUser = $this->createForm(ViewerType::class, $viewerUser);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $entityManagerUser = $this->getDoctrine()->getManager();
            $viewerUser->setPermissionUser($this->getUser());
            $entityManagerUser->persist($viewerUser);
            $entityManagerUser->flush();
        }

        $form = $this->createForm(RecruiterType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $vacancy->setAssignedBy($this->getUser());
            $vacancy->setAssigneeDate(new DateTimeImmutable('now'));
            $vacancy->setStatus('Issue have been assigned ');
            $entityManager->persist($vacancy);
            $entityManager->flush();
        }

        return $this->render('recruiting/vacancy/show.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView(),
            'formUser' => $formUser->createView(),
            self::VACANCY_EXPIRED_TIME => $timeCalculator->getExpiredTime($vacancyTimeDecorator->expiredTimeDecorator(), new DateTime())
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/{id}", name="vacancy_show_recruiter", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param ExpiredTimeCalculator $timeCalculator
     * @return Response
     * @throws Exception
     */

    public function showRecruiter(Vacancy $vacancy, ExpiredTimeCalculator $timeCalculator): Response
    {
        $vacancyTimeDecorator = new VacancyTimeDecorator($vacancy);

            return  $this->render('recruiting/vacancy/showRecruiter/showRecruiter.html.twig', [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy,
                self::VACANCY_EXPIRED_TIME => $timeCalculator->getExpiredTime($vacancyTimeDecorator->expiredTimeDecorator(),
                    new DateTime()),
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidates/{id}", name="vacancy_show_candidates", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param CandidateVacancyAmountCheckLogic $amountCheckLogic
     * @return NoDateException|Response
     */
    public function recruiterVacancyCandidates(Vacancy $vacancy, CandidateVacancyAmountCheckLogic $amountCheckLogic)
    {
        $amountCheckLogic->changeVacancyStatus($vacancy);
        return  $this->render('recruiting/vacancy/showRecruiter/showVacancyCandidates.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/received/{id}", name="vacancy_show_search_candidate", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @return NoDateException|Response
     */
    public function recruiterStatusSearchCandidate(Vacancy $vacancy)
    {
        return  $this->render('recruiting/vacancy/showRecruiter/recruiterStatusSearchCandidate.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
        ]);
    }


    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/received/fromVacancy/{id}", name="vacancy_show_from_vacancy", methods={"GET","POST"})
     * @param VacancyLink $vacancyLink
     * @param Request $request
     * @param CandidateRepository $candidateRepository
     * @param VacancyRepository $vacancyRepository
     * @param StrategyExistenceLinks $strategyExistenceLinks
     * @param StrategyNonExistenceLinks $strategyNonExistenceLinks
     * @return NoDateException|RedirectResponse|Response
     * @throws NoDateException
     */
    public function receivedFromVacancy(
        VacancyLink $vacancyLink,
        Request $request,
        CandidateRepository $candidateRepository,
        VacancyRepository $vacancyRepository,
        StrategyExistenceLinks $strategyExistenceLinks,
        StrategyNonExistenceLinks $strategyNonExistenceLinks
    )
    {
        $candidateLink = new CandidateLink();
        $form = $this->createForm(CandidateStepCvReceivedType::class, $candidateLink,
            [self::CONSTRAINT => [new CandidateLinkCheckExistence($vacancyLink)]]);
        $form->handleRequest($request);
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        if (!empty($vacancyId)) {
            $vacancy = $vacancyRepository->findOneBy(['id' => $vacancyId]);
        }
        if (!isset($vacancy)){
            throw new NoDateException('No data about vacancy');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get(self::CANDIDATE_NAME)->getData();
            $surname = $form->get(self::CANDIDATE_SURNAME)->getData();
            $existsCandidateBuilder = new ExistsCandidateBuilderLinks($candidateRepository);
            $existsUser = $existsCandidateBuilder->build(
                $name,
                $surname);
            if ($existsUser !== null) {
                $context = new ContextForRelationStrategyLinks(
                    $strategyExistenceLinks
                );
            } else {
                $context = new ContextForRelationStrategyLinks(
                    $strategyNonExistenceLinks);
            }
            $candidate = $context->execute(
                $vacancyLink,
                $candidateLink,
                $name,
                $surname,
                self::VACANCY_ENTITY_IN_VIEW,
                $candidateLink->getReceivedCv()
                );

            return $this->redirectToRoute(self::CANDIDATE_EDIT, [
                'id' => $candidate->getId(),
                'vacancyLink' => $vacancyLink->getId()
            ]);

        }
        return $this->render('recruiting/vacancy/showRecruiter/stepCvReceived/stepCvReceivedFromVacancy.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/received/fromHunting/{id}", name="vacancy_show_from_hunting", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateRepository $candidateRepository
     * @param StrategyExistence $strategyExistence
     * @param StrategyNonExistence $strategyNonExistence
     * @return NoDateException|RedirectResponse|Response
     */
    public function receivedFromHunting(
        Vacancy $vacancy,
        Request $request,
        CandidateRepository $candidateRepository,
        StrategyExistence $strategyExistence,
        StrategyNonExistence $strategyNonExistence
    )
    {
        $candidateVacancy = new CandidateVacancy();
        $form = $this->createForm(CandidateStepCvReceivedTypeForHunting::class, $candidateVacancy,
            [self::CONSTRAINT => [new CandidateVacancyCheckExistence($vacancy)]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get(self::CANDIDATE_NAME)->getData();
            $surname = $form->get(self::CANDIDATE_SURNAME)->getData();
            $existsCandidateBuilder = new ExistsCandidateBuilder($candidateRepository);
            $existsUser = $existsCandidateBuilder->build($name, $surname);
            if ($existsUser !== null) {
                $context = new ContextForRelationStrategy(
                    $strategyExistence
                );
            } else {
                $context = new ContextForRelationStrategy(
                    $strategyNonExistence);
            }
            $candidate = $context->execute($vacancy,
                $candidateVacancy,
                $name,
                $surname,
                'hunting',
                $candidateVacancy->getReceivedCv()
                );
            return $this->redirectToRoute(self::CANDIDATE_EDIT, [
                'id' => $candidate->getId(),
                self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
            ]);

        }
        return $this->render('recruiting/vacancy/showRecruiter/stepCvReceived/stepCvReceivedFromHunting.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView()
        ]);
    }


    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/received/fromReccomandation/{id}", name="vacancy_show_from_recommendation", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateRepository $candidateRepository
     * @param StrategyExistence $strategyExistence
     * @param StrategyNonExistence $strategyNonExistence
     * @return NoDateException|RedirectResponse|Response
     */
    public function receivedFromRecommendation(
        Vacancy $vacancy,
        Request $request,
        CandidateRepository $candidateRepository,
        StrategyExistence $strategyExistence,
        StrategyNonExistence $strategyNonExistence
    )
    {
        $candidateVacancy = new CandidateVacancy();
        $form = $this->createForm(CandidateStepCvReceivedTypeForRecommendation::class, $candidateVacancy,
            [self::CONSTRAINT => [new CandidateVacancyCheckExistence($vacancy)]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get(self::CANDIDATE_NAME)->getData();
            $surname = $form->get(self::CANDIDATE_SURNAME)->getData();
            $existsCandidateBuilder = new ExistsCandidateBuilder($candidateRepository);
            $existsUser = $existsCandidateBuilder->build($name, $surname);
            if ($existsUser !== null) {
                $context = new ContextForRelationStrategy(
                    $strategyExistence
                );
            } else {
                $context = new ContextForRelationStrategy(
                    $strategyNonExistence);
            }
            $candidate = $context->execute($vacancy,
                $candidateVacancy,
                $name,
                $surname,
                'hunting',
                $candidateVacancy->getReceivedCv()
            );

            return $this->redirectToRoute(self::CANDIDATE_EDIT, [
                'id' => $candidate->getId(),
                'vacancy' => $vacancy->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/stepCvReceived/stepCvReceivedFromRecommendation.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView()
        ]);
    }


//    /**
//     * @IsGranted("ROLE_RECRUITER")
//     * @Route("/recruiter/history/{id}", name="vacancy_show_history", methods={"GET","POST"})
//     * @param Vacancy $vacancy
//     * @return Response
//     */
//    public function history(Vacancy $vacancy): Response
//    {
//        return  $this->render('recruiting/vacancy/showRecruiter/history.html.twig', [
//            self::VACANCY_ENTITY_IN_VIEW => $vacancy
//        ]);
//    }


    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/history/in-vacancy/{id}", name="vacancy_show_history_in_vacancy", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @return Response
     */
    public function historyCandidateInVacancy(Vacancy $vacancy,
                                                Request $request,
                                                CandidateForms $candidateForms): Response
    {
        $candidateVacancy = null;
        $candidateLink = null;
        if ($request->get(self::CANDIDATE_VACANCY) !== null){
            $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
        }
        if ($request->get(self::CANDIDATE_LINK) !== null){
            $candidateLink = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));
        }
        return  $this->render('recruiting/vacancy/showRecruiter/historyCandidateInVacancy.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'links' => $candidateForms->vacancyLink($vacancy),
            self::CANDIDATE_VACANCY => $candidateVacancy,
            self::CANDIDATE_LINK => $candidateLink
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vacancy_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Vacancy $vacancy
     * @return Response
     */
    public function edit(Request $request, Vacancy $vacancy): Response
    {
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vacancy_edit', [
                'id' => $vacancy->getId(),
            ]);
        }

        return $this->render('recruiting/vacancy/edit.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vacancy_delete", methods={"DELETE"})
     * @param Request $request
     * @param Vacancy $vacancy
     * @return Response
     */
    public function delete(Request $request, Vacancy $vacancy): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vacancy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vacancy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vacancy_index');
    }

}
