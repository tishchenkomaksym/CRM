<?php


namespace App\Controller\Recruiting;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\CandidateLink;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use App\Form\Recruiting\CandidateLink\CandidateLinkCommentInterestType;
use App\Form\Recruiting\CandidateLink\CandidateLinkDenialReasonType;
use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateRepository;
use App\Repository\VacancyLinkRepository;
use App\Repository\VacancyRepository;

use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\CandidateVacancyHistory\CandidateVacancyHistoryDataProvider;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators\CandidateLinkSearch;
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

class CandidateLinkController extends AbstractController
{
    public const CANDIDATE_ID = 'candidateId';

    public const  VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const LINKS = 'links';

    public const VACANCY_SHOW_RECEIVED = 'vacancy_show_cv_received';

    public const CANDIDATE_INTERESTED_IN_VACANCY = 'Candidate is interested in vacancy';
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
     * @Route("/recruiter/links/{id}", name="candidate_list_links", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param VacancyLinkRepository $vacancyLinkRepository
     * @return Response
     */
    public function showListCandidateLinks(Vacancy $vacancy,
                                    VacancyLinkRepository $vacancyLinkRepository): Response
    {
        return $this->render('recruiting/candidateLinks/listCandidateLinksFromVacancy.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            self::LINKS => $vacancyLinkRepository->findBy([
                self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
            ])
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/links/recommendation/{id}", name="candidate_list_links_recommendation", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param VacancyLinkRepository $vacancyLinkRepository
     * @return Response
     */
    public function showListCandidateLinksRecommendation(Vacancy $vacancy,
        VacancyLinkRepository $vacancyLinkRepository): Response
    {
        return $this->render('recruiting/candidateLinks/listCandidateLinksFromRecommendation.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            self::LINKS => $vacancyLinkRepository->findBy([
                self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
            ])
        ]);
    }


    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/links/candidate/{id}", name="candidate_links", methods={"GET","POST"})
     * @param VacancyLink $vacancyLink
     * @param Request $request
     * @param VacancyRepository $vacancyRepository
     * @return Response
     */
    public function showCandidateLinks(VacancyLink $vacancyLink,
                                       Request $request,
                                       VacancyRepository $vacancyRepository ): Response
    {
        $vacancy = '';
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        if (!empty($vacancyId)) {
            $vacancy = $vacancyRepository->findOneBy(['id' => $vacancyId]);
        }
        return $this->render('recruiting/candidateLinks/candidateLinksFromVacancy.html.twig', [
            self::LINKS => $vacancyLink,
            self::VACANCY_ENTITY_IN_VIEW => $vacancy
        ]);
    }



    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/links/candidate/recommendation/{id}", name="candidate_links_recommendation", methods={"GET","POST"})
     * @param VacancyLink $vacancyLink
     * @param Request $request
     * @param VacancyRepository $vacancyRepository
     * @return Response
     */
    public function showCandidateLinksRecommendation(VacancyLink $vacancyLink,
        Request $request,
        VacancyRepository $vacancyRepository ): Response
    {
        $vacancy = '';
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        if (!empty($vacancyId)) {
            $vacancy = $vacancyRepository->findOneBy(['id' => $vacancyId]);
        }
        return $this->render('recruiting/candidateLinks/candidateLinksFromRecommendation.html.twig', [
            self::LINKS => $vacancyLink,
            self::VACANCY_ENTITY_IN_VIEW => $vacancy
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/checklink/{id}", name="check_interest_link", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateLinkSearch $candidateLinkSearch
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @param Swift_Mailer $mailer
     * @param CandidateRepository $candidateRepository
     * @param CandidateVacancyHistoryDataProvider $candidateVacancyHistoryData
     * @return NoDateException|Response
     * @throws LoaderError
     * @throws NoDateException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function checkInterest(Vacancy $vacancy, Request $request,
        CandidateLinkSearch $candidateLinkSearch,
        CandidatePhotoDecorator $candidatePhotoDecorator,
        Swift_Mailer $mailer,
        CandidateRepository $candidateRepository,
        CandidateVacancyHistoryDataProvider $candidateVacancyHistoryData
)
    {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateLink = $candidateLinkSearch->searchCandidateLink($candidateId, $vacancy);
        if($candidateLink === null){
            throw new NoDateException('CandidateLink not Found');
        }
        $receivedCv = $candidateLink->getReceivedCv();
        $candidatePhotoDecorator->receivedCvNotNullCandidateLink($candidateLink);
        $form = $this->createForm(CandidateLinkCommentInterestType::class, $candidateLink);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $candidateLink->setReceivedCv($receivedCv);
            if ($candidateLink->getCandidate() === null){
                throw new NoDateException('Candidate not Found');
            }
            if($form instanceof Form)
            {
                if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                    $candidateLink->setCandidateStatus(self::CANDIDATE_INTERESTED_IN_VACANCY);
                    $candidateVacancyHistoryData->candidateLinkCreate($candidateLink, self::CANDIDATE_INTERESTED_IN_VACANCY);
                    $entityManager->persist($candidateLink);
                    $entityManager->flush();
                    $messageBuilder = new NewMessageBuilderForDepartmentManagerCandidateApprove(
                        $vacancy, $candidateId, $candidateRepository, $this->environment
                    );
                    if ($vacancy->getVacancyViewerUser() !== null){
                        $messageBuilderViewer = new NewMessageBuilderForViewer(
                            $vacancy, $candidateId, $candidateRepository, $this->environment
                        );
                        $mailer->send($messageBuilderViewer->build());
                    }
                    $mailer->send($messageBuilder->build());
                    return $this->redirectToRoute('vacancy_show_candidates', [
                        'id' => $vacancy->getId()]);
                }

                $candidateLink->setCandidateStatus('Closed by recrutier');
                $entityManager->persist($candidateLink);
                $candidateVacancyHistoryData->candidateLinkCreate($candidateLink, self::CANDIDATE_INTERESTED_IN_VACANCY);
                $entityManager->flush();
                return $this->redirectToRoute('checked_interest_link', [
                        'id' => $vacancy->getId(),
                        self::CANDIDATE_ID => $candidateId
                    ]
                );
            }
        }

        return  $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/checkCandidateInterest.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recruiter/candidate/interest/checklink/{id}/edit", name="check_interest_link_edit", methods={"GET","POST"})
     * @param Request $request
     * @param CandidateLink $candidateLink
     * @return Response
     */
    public function editComment(CandidateLink $candidateLink,  Request $request): Response
    {
        $form = $this->createForm(CandidateLinkCommentInterestType::class, $candidateLink);
        $form->handleRequest($request);
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $entityManager = $this->getDoctrine()->getManager();
            if($form instanceof Form){
                if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                    $candidateLink->setCandidateStatus(self::CANDIDATE_INTERESTED_IN_VACANCY);
                    $entityManager->persist($candidateLink);
                    $entityManager->flush();
                    return $this->redirectToRoute(self::VACANCY_SHOW_RECEIVED, [
                        'id' => $vacancyId,
                    ]);
                }
                $candidateLink->setCandidateStatus('Closed by recrutier');
                $entityManager->persist($candidateLink);
                $entityManager->flush();
                return $this->redirectToRoute('checked_interest_link_denial_edit', [
                        'id' => $candidateLink->getId(),
                        'vacancy' => $vacancyId
                    ]
                );
            }
        }

        return $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/editComment.html.twig', [
            'candidateLink' => $candidateLink,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/checkedlink/{id}", name="checked_interest_link", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateLinkSearch $candidateLinkSearch
     * @return NoDateException|Response
     */
    public function interestIsChecked(Vacancy $vacancy, Request $request, CandidateLinkSearch $candidateLinkSearch)
    {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateLink = $candidateLinkSearch->searchCandidateLink($candidateId, $vacancy);
        $form = $this->createForm(CandidateLinkDenialReasonType::class, $candidateLink);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidateLink);
            $entityManager->flush();
            return $this->redirectToRoute('vacancy_show_candidates', [
                    'id' => $vacancy->getId()
                ]
            );
        }
        return $this->render('recruiting/vacancy/showRecruiter/stepCandidateInterest/candidateInterestIsChecked.html.twig', [
                'id' => $vacancy->getId(),
                'form' => $form->createView(),
                self::CANDIDATE_ID => $candidateId
            ]
        );
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/checkedlink/edit/{id}", name="checked_interest_link_denial_edit", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateLinkRepository $candidateLinkRepository
     * @return NoDateException|Response
     */
    public function interestIsCheckedEdit(Vacancy $vacancy, Request $request, CandidateLinkRepository $candidateLinkRepository)
    {
        $candidateLink = $candidateLinkRepository->findOneBy(['id' => $request->get('id')]);
        $form = $this->createForm(CandidateLinkDenialReasonType::class, $candidateLink);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidateLink);
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