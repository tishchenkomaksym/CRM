<?php


namespace App\Controller\Recruiting;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use App\Form\Recruiting\CandidateVacancy\CandidateVacancyCommentInterestType;
use App\Form\Recruiting\CandidateVacancy\CandidateVacancyDenialReasonType;
use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateRepository;
use App\Repository\CandidateVacancyRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators\CandidateVacancySearch;
use App\Service\Vacancy\Letters\CreateForDepartmentManagerCandidateApprove\NewMessageBuilderForDepartmentManagerCandidateApprove;
use App\Service\Vacancy\Letters\CreateForViewerCandidateApprove\NewMessageBuilderForViewer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @IsGranted("ROLE_VACANCY_VIEWER_USER")
 */
class CandidateVacancyController extends AbstractController
{

    public const CANDIDATE_ID = 'candidateId';

    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const VACANCY_SHOW_RECEIVED = 'vacancy_show_cv_received';
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {

        $this->environment = $environment;
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/{id}", name="vacancy_show_cv_received", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param CandidateVacancyRepository $candidateVacancyRepository
     * @param CandidateLinkRepository $candidateLinkRepository
     * @return NoDateException|Response
     */
    public function recruiterStatusCvReceived(
        Vacancy $vacancy,
        CandidateVacancyRepository $candidateVacancyRepository,
        CandidateLinkRepository $candidateLinkRepository
    ) {
        $notCheckedCandidateHunting = $candidateVacancyRepository->findBy([
            'commentInterest' => null,
            self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
        ]);

        $notCheckedCandidate = $candidateLinkRepository->findBy([
            'commentInterest' => null,
            'vacancyLink' => $vacancy->getVacancyLinks()->current()
        ]);
        $displayDone = false;
        if (empty($notCheckedCandidateHunting) && empty($notCheckedCandidate)) {
            $displayDone = true;
        }

        return $this->render('recruiting/vacancy/showRecruiter/recruiterStatusCvReceived.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'displayDone' => $displayDone
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/check/{id}", name="check_interest", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateVacancySearch $candidateVacancySearch
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @param Swift_Mailer $mailer
     * @param CandidateRepository $candidateRepository
     * @return NoDateException|Response
     * @throws NoDateException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function checkInterest(
        Vacancy $vacancy,
        Request $request,
        CandidateVacancySearch $candidateVacancySearch,
        CandidatePhotoDecorator $candidatePhotoDecorator,
        Swift_Mailer $mailer,
        CandidateRepository $candidateRepository
    ) {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateVacancy = $candidateVacancySearch->searchCandidateVacancy($candidateId, $vacancy->getId());
        if ($candidateVacancy === null) {
            throw new NoDateException('CandidateVacancy not Found');
        }
        $candidatePhotoDecorator->receivedCvNotNull($candidateVacancy);
        $form = $this->createForm(CandidateVacancyCommentInterestType::class, $candidateVacancy);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            if ($candidateVacancy->getCandidate() === null) {
                throw new NoDateException('Candidate not Found');
            }
            if ($form instanceof Form) {
                if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                    $candidateVacancy->setCandidateStatus('Candidate is interested in vacancy');
                    $entityManager->persist($candidateVacancy);
                    $entityManager->flush();
                    $messageBuilder = new NewMessageBuilderForDepartmentManagerCandidateApprove(
                        $vacancy, $candidateId, $candidateRepository,  $this->environment
                    );
                    if ($vacancy->getVacancyViewerUser() !== null){
                        $messageBuilderViewer = new NewMessageBuilderForViewer(
                            $vacancy, $candidateId, $candidateRepository, $this->environment
                        );
                        $mailer->send($messageBuilderViewer->build());
                    }
                    $mailer->send($messageBuilder->build());
                    return $this->redirectToRoute('vacancy_show_candidates', [
                        'id' => $vacancy->getId()
                    ]);
                }

                $candidateVacancy->setCandidateStatus('Closed');
                $entityManager->persist($candidateVacancy);
                $entityManager->flush();
                return $this->redirectToRoute('checked_interest', [
                        'id' => $vacancy->getId(),
                        self::CANDIDATE_ID => $candidateId
                    ]
                );
            }
        }

        return $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/checkCandidateInterest.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/recruiter/candidate/interest/check/{id}/edit", name="check_interest_edit", methods={"GET","POST"})
     * @param Request $request
     * @param CandidateVacancy $candidateVacancy
     * @return Response
     */
    public function editComment(Request $request, CandidateVacancy $candidateVacancy): Response
    {
        $form = $this->createForm(CandidateVacancyCommentInterestType::class, $candidateVacancy);
        $form->handleRequest($request);
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $entityManager = $this->getDoctrine()->getManager();
            if($form instanceof Form){
                if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                    $candidateVacancy->setCandidateStatus('Candidates Interest is checked');
                    $entityManager->persist($candidateVacancy);
                    $entityManager->flush();
                    return $this->redirectToRoute(self::VACANCY_SHOW_RECEIVED, [
                        'id' => $vacancyId
                    ]);
                }
                $candidateVacancy->setCandidateStatus('Closed');
                $entityManager->persist($candidateVacancy);
                $entityManager->flush();
                return $this->redirectToRoute('checked_interest_denial_edit', [
                        'id' => $candidateVacancy->getId(),
                        self::VACANCY_ENTITY_IN_VIEW => $vacancyId
                    ]
                );
            }
        }

        return $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/editComment.html.twig', [
            'candidateVacancy' => $candidateVacancy,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/checked/{id}", name="checked_interest", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateVacancySearch $candidateVacancySearch
     * @return NoDateException|Response
     */
    public function interestIsChecked(
        Vacancy $vacancy,
        Request $request,
        CandidateVacancySearch $candidateVacancySearch
    ) {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateVacancy = $candidateVacancySearch->searchCandidateVacancy($candidateId, $vacancy->getId());
        $form = $this->createForm(CandidateVacancyDenialReasonType::class, $candidateVacancy);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidateVacancy);
            $entityManager->flush();
            return $this->redirectToRoute('vacancy_show_candidates', [
                    'id' => $vacancy->getId()
                ]
            );
        }
        return $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/candidateInterestIsChecked.html.twig', [
                'id' => $vacancy->getId(),
                'form' => $form->createView(),
                self::CANDIDATE_ID => $candidateId,
            ]
        );
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/checked/edit/{id}", name="checked_interest_denial_edit", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateVacancyRepository $candidateLinkRepository
     * @return NoDateException|Response
     */
    public function interestIsCheckedEdit(Vacancy $vacancy, Request $request, CandidateVacancyRepository $candidateLinkRepository)
    {
        $candidateVacancy = $candidateLinkRepository->findOneBy(['id' => $request->get('id')]);
        $form = $this->createForm(CandidateVacancyDenialReasonType::class, $candidateVacancy);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidateVacancy);
            $entityManager->flush();
            return $this->redirectToRoute(self::VACANCY_SHOW_RECEIVED, [
                    'id' => $vacancy->getId()
                ]
            );
        }
        return $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/candidateInterestIsChecked.html.twig', [
                'id' => $vacancy->getId(),
                'form' => $form->createView()
            ]
        );
    }

}