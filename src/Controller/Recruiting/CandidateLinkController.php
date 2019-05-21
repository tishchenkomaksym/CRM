<?php


namespace App\Controller\Recruiting;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use App\Form\CandidateLinkCommentInterestType;
use App\Form\CandidateLinkDenialReasonType;
use App\Repository\VacancyLinkRepository;
use App\Repository\VacancyRepository;

use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators\CandidateLinkSearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateLinkController extends AbstractController
{
    public const CANDIDATE_ID = 'candidateId';

    public const  VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const LINKS = 'links';

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
        return $this->render('vacancy/listCandidateLinksFromVacancy.html.twig', [
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
        return $this->render('vacancy/listCandidateLinksFromRecommendation.html.twig', [
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
        return $this->render('vacancy/candidateLinksFromVacancy.html.twig', [
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
        return $this->render('vacancy/candidateLinksFromRecommendation.html.twig', [
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
     * @return NoDateException|Response
     * @throws NoDateException
     */
    public function checkInterest(Vacancy $vacancy, Request $request,
        CandidateLinkSearch $candidateLinkSearch,
        CandidatePhotoDecorator $candidatePhotoDecorator)
    {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateLink = $candidateLinkSearch->searchCandidateLink($candidateId, $vacancy);
        if($candidateLink === null){
            throw new NoDateException('CandidateLink not Found');
        }
        $candidatePhotoDecorator->receivedCvNotNullCandidateLink($candidateLink);
        $form = $this->createForm(CandidateLinkCommentInterestType::class, $candidateLink);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            if ($candidateLink->getCandidate() === null){
                throw new NoDateException('Candidate not Found');
            }
            if($form instanceof Form)
            {
                if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                    $candidateLink->setCandidateStatus('Candidates Interest is checked');
                    $entityManager->persist($candidateLink);
                    $entityManager->flush();
                    return $this->redirectToRoute('vacancy_show_cv_received', [
                        'id' => $vacancy->getId()]);
                }

                $candidateLink->setCandidateStatus('Closed');
                $entityManager->persist($candidateLink);
                $entityManager->flush();
                return $this->redirectToRoute('checked_interest_link', [
                        'id' => $vacancy->getId(),
                        self::CANDIDATE_ID => $candidateId
                    ]
                );
            }
        }

        return  $this->render('vacancy/checkCandidateInterest.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'form' => $form->createView()
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
            return $this->redirectToRoute('vacancy_show_cv_received', [
                    'id' => $vacancy->getId()
                ]
            );
        }
        return $this->render('vacancy/candidateInterestIsChecked.html.twig', [
                'id' => $vacancy->getId(),
                'form' => $form->createView(),
                self::CANDIDATE_ID => $candidateId
            ]
        );
    }

}