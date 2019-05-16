<?php

namespace App\Controller\Recruiting;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Form\CandidateVacancyIdType;
use App\Repository\CandidateRepository;
use App\Repository\CandidateVacancyRepository;
use App\Repository\VacancyRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\Candidate\VacancyFieldDecorator;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators\CandidateVacancyCheckExistenceUpdateCandidate;
use DateTime;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/candidate")
 */
class CandidateController extends AbstractController
{
    public const UPLOADS_DIRECTORY = 'uploads_directory';

    public const CANDIDATE = 'candidate';

    public const CANDIDATE_INDEX = 'candidate_index';

    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    /**
     * @Route("/", name="candidate_index", methods={"GET"})
     * @param CandidateRepository $candidateRepository
     * @return Response
     */
    public function index(CandidateRepository $candidateRepository): Response
    {
        return $this->render('candidate/index.html.twig', [
            'candidates' => $candidateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="candidate_new", methods={"GET","POST"})
     * @param Request $request
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @param VacancyFieldDecorator $vacancyFieldDecorator
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, CandidatePhotoDecorator $candidatePhotoDecorator,
                        VacancyFieldDecorator $vacancyFieldDecorator): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            $vacancyFieldDecorator->vacancyField($vacancyIds, $candidate);

            if ($candidate->getPhoto() !== null) {
                /** @var UploadedFile $file */
                $file = $candidate->getPhoto();
                $fileName = $candidatePhotoDecorator->upload($file);
                $candidate->setPhoto($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $candidate->setCreatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute(self::CANDIDATE_INDEX);
        }

        return $this->render('candidate/new.html.twig', [
            self::CANDIDATE => $candidate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="candidate_show", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param Request $request
     * @param VacancyFieldDecorator $vacancyFieldDecorator
     * @return Response
     */
    public function show(Candidate $candidate, Request $request,
                         VacancyFieldDecorator $vacancyFieldDecorator): Response
    {
        $form = $this->createForm(CandidateVacancyIdType::class, $candidate,
            ['constraints' => [new CandidateVacancyCheckExistenceUpdateCandidate()]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            $vacancyFieldDecorator->vacancyField($vacancyIds, $candidate);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute(self::CANDIDATE_INDEX);
        }

        $vacancy = $candidate->getCandidateVacancies();
        return $this->render('candidate/show.html.twig', [
            self::CANDIDATE => $candidate,
            'vacancies' => $vacancy,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/{id}/edit", name="candidate_edit", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param Request $request
     * @param VacancyRepository $vacancyRepository
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @param VacancyFieldDecorator $vacancyFieldDecorator
     * @param CandidateVacancyRepository $candidateVacancyRepository
     * @return Response
     * @throws NoDateException
     */

    public function edit(Candidate $candidate, Request $request, VacancyRepository $vacancyRepository,
                         CandidatePhotoDecorator $candidatePhotoDecorator,
                         VacancyFieldDecorator $vacancyFieldDecorator,
                         CandidateVacancyRepository $candidateVacancyRepository): Response
    {
        $photo = $candidate->getPhoto();
        $candidatePhotoDecorator->photoNotNull($candidate);

        $form = $this->createForm(CandidateType::class, $candidate,
            ['constraints' => [new CandidateVacancyCheckExistenceUpdateCandidate()]]);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            // Check existence of vacancy
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            $vacancyFieldDecorator->vacancyField($vacancyIds, $candidate);

            /** @var UploadedFile $file */
            $file = $candidate->getPhoto();
            if ($file !== null) {
                $fileName = $candidatePhotoDecorator->upload($file);
                $candidate->setPhoto($fileName);
            }else{
                $candidate->setPhoto($photo);
            }
            $candidate->setUpdatedDate(new DateTime('now'));

            $this->getDoctrine()->getManager()->flush();
            $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
            if (!empty($vacancyId)) {
                $vacancy = $vacancyRepository->findOneBy(['id' => $vacancyId]);
                if ($vacancy === null){
                    throw new NoDateException('Vacancy not found');
                }
                $candidateVacancy = $candidateVacancyRepository->findOneBy([
                    'candidate' => $candidate->getId(),
                    'vacancy' => $vacancy->getId()
                ]);
                if ($candidateVacancy === null){
                    throw new NoDateException('CandidateVacancy not found');
                }
                $entityManager->persist($candidateVacancy->setCandidateStatus('CV Received'));
                $entityManager->persist($vacancy->setStatus('CV Received'));
                $entityManager->flush();
                return $this->redirectToRoute('vacancy_show_search_candidate', [
                        'id' => $vacancyId
                    ]
                );
            }

            return $this->redirectToRoute(self::CANDIDATE_INDEX, [
                'id' => $candidate->getId(),
            ]);
        }
        return $this->render('candidate/edit.html.twig', [
            self::CANDIDATE => $candidate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="candidate_delete", methods={"DELETE"})
     * @param Request $request
     * @param Candidate $candidate
     * @return Response
     */
    public function delete(Request $request, Candidate $candidate): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($candidate);
            $entityManager->flush();
        }

        return $this->redirectToRoute(self::CANDIDATE_INDEX);
    }
}
