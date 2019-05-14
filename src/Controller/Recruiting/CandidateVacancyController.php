<?php


namespace App\Controller\Recruiting;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Form\CandidateVacancyCommentInterestType;
use App\Form\CandidateVacancyDenialReasonType;
use App\Repository\CandidateVacancyRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators\CandidateVacancySearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_VACANCY_VIEWER_USER")
 */

class CandidateVacancyController extends AbstractController
{

    public const CANDIDATE_ID = 'candidateId';

    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/{id}", name="vacancy_show_cv_received", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param CandidateVacancyRepository $candidateVacancyRepository
     * @return NoDateException|Response
     */
    public function recruiterStatusCvReceived(Vacancy $vacancy,
                                              CandidateVacancyRepository $candidateVacancyRepository)
    {
        $notCheckedCandidate = $candidateVacancyRepository->findBy([
            'commentInterest' => null,
            'vacancy' => $vacancy->getId()
        ]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($vacancy->setStatus('Candidates Interest is checked'));
        $entityManager->flush();

        return  $this->render('vacancy/recruiterStatusCvReceived.html.twig', [
            self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            'checkComment' => $notCheckedCandidate
        ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/recruiter/candidate/interest/check/{id}", name="check_interest", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateVacancySearch $candidateVacancySearch
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @return NoDateException|Response
     * @throws NoDateException
     */
    public function checkInterest(Vacancy $vacancy, Request $request,
                                  CandidateVacancySearch $candidateVacancySearch,
                                  CandidatePhotoDecorator $candidatePhotoDecorator)
    {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateVacancy = $candidateVacancySearch->searchCandidateVacancy($candidateId, $vacancy->getId());
        if($candidateVacancy === null){
            throw new NoDateException('CandidateVacancy not Found');
        }
        $candidatePhotoDecorator->receivedCvNotNull($candidateVacancy);
        $form = $this->createForm(CandidateVacancyCommentInterestType::class, $candidateVacancy);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            if ($candidateVacancy->getCandidate() === null){
                throw new NoDateException('Candidate not Found');
            }
            if($form instanceof Form)
            {
                if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                    $candidateVacancy->getCandidate()->setStatus('Candidates Interest is checked');
                    $entityManager->persist($candidateVacancy);
                    $entityManager->flush();
                    return $this->redirectToRoute('vacancy_show_cv_received', [
                    'id' => $vacancy->getId()]);
                }

                    $candidateVacancy->getCandidate()->setStatus('Closed');
                    $entityManager->persist($candidateVacancy);
                    $entityManager->flush();
                    return $this->redirectToRoute('checked_interest', [
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
     * @Route("/recruiter/candidate/interest/checked/{id}", name="checked_interest", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateVacancySearch $candidateVacancySearch
     * @return NoDateException|Response
     */
    public function interestIsChecked(Vacancy $vacancy, Request $request, CandidateVacancySearch $candidateVacancySearch)
    {
        $candidateId = $request->get(self::CANDIDATE_ID);
        $candidateVacancy = $candidateVacancySearch->searchCandidateVacancy($candidateId, $vacancy->getId());
        $form = $this->createForm(CandidateVacancyDenialReasonType::class, $candidateVacancy);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidateVacancy);
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