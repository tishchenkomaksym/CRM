<?php

namespace App\Controller\Recruiting;

use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Form\CandidateType;
use App\Form\CandidateVacancyIdType;
use App\Repository\CandidateRepository;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators\CandidateVacancyCheckExistenceUpdateCandidate;
use DateTime;
use DateTimeImmutable;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_CANDIDATES_DATABASE_USER")
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
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            foreach ($vacancyIds as $vacancyId) {
                $candidateVacancy = new CandidateVacancy();
                $candidateVacancy->setCandidate($candidate);
                $candidateVacancy->setVacancy($vacancyId);
                $entityManager->persist($candidateVacancy);
            }


            if ($candidate->getPhoto() !== null) {
                /** @var UploadedFile $file */
                $file = $candidate->getPhoto();
                $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
                $file->move($this->getParameter(self::UPLOADS_DIRECTORY), $fileName);
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
     * @return Response
     */
    public function show(Candidate $candidate, Request $request): Response
    {
        $form = $this->createForm(CandidateVacancyIdType::class, $candidate,
            ['constraints' => [new CandidateVacancyCheckExistenceUpdateCandidate()]]);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            foreach ($vacancyIds as $vacancyId) {
                $candidateVacancy = new CandidateVacancy();
                $candidateVacancy->setCandidate($candidate);
                $candidateVacancy->setVacancy($vacancyId);
                $entityManager->persist($candidateVacancy);
            }

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
     * @return Response
     * @throws Exception
     */

    public function edit(Candidate $candidate, Request $request): Response
    {
        $photoNotNull = $candidate->getPhoto() !== null;
        $photo = $candidate->getPhoto();
        if ($photoNotNull) {
            $candidate->setPhoto(
                new File($this->getParameter(self::UPLOADS_DIRECTORY) . '/' . $candidate->getPhoto(), false)
            );
        }
        $form = $this->createForm(CandidateType::class, $candidate,
            ['constraints' => [new CandidateVacancyCheckExistenceUpdateCandidate()]]);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            // Check existence of vacancy
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            // set Vacancy and Candidate to CandidateVacancyCheckExistenceUpdateCandidate
            foreach ($vacancyIds as $vacancyId) {
                $candidateVacancy = new CandidateVacancy();
                $candidateVacancy->setCandidate($candidate);
                $candidateVacancy->setVacancy($vacancyId);
                $entityManager->persist($candidateVacancy);
            }

            /** @var UploadedFile $file */
            $file = $candidate->getPhoto();
            if ($file !== null) {
                $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
                $file->move($this->getParameter('uploads_directory'), $fileName);
                $candidate->setPhoto($fileName);
            }
            else {
                $candidate->setPhoto($photo);
            }
            $candidate->setUpdatedDate(new DateTime('now'));

            $this->getDoctrine()->getManager()->flush();
            if (!empty($request->get(self::VACANCY_ENTITY_IN_VIEW))) {
                return $this->redirectToRoute('vacancy_show_cv-received', [
                        'id' => $request->get(self::VACANCY_ENTITY_IN_VIEW)
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
