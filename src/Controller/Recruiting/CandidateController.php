<?php

namespace App\Controller\Recruiting;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Candidate;
use App\Form\Recruiting\CandidateType;
use App\Form\Recruiting\CandidateVacancy\CandidateVacancyIdType;
use App\Repository\CandidateRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\Candidate\VacancyFieldDecorator;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\CandidateEditRelations;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators\CandidateVacancyCheckExistenceUpdateCandidate;
use DateTime;
use DateTimeImmutable;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('recruiting/candidate/index.html.twig', [
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
    public function new(
        Request $request,
        CandidatePhotoDecorator $candidatePhotoDecorator,
        VacancyFieldDecorator $vacancyFieldDecorator
    ): Response {
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

        return $this->render('recruiting/candidate/new.html.twig', [
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
    public function show(
        Candidate $candidate,
        Request $request,
        VacancyFieldDecorator $vacancyFieldDecorator
    ): Response {
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
        return $this->render('recruiting/candidate/show.html.twig', [
            self::CANDIDATE => $candidate,
            'vacancies' => $vacancy,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/{id}/edit", name="candidate_edit", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param Request $request
     * @param CandidateEditRelations $candidateEditRelations
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @param VacancyFieldDecorator $vacancyFieldDecorator
     * @return Response
     * @throws NoDataException
     * @throws NoDateException
     */

    public function edit(
        Candidate $candidate,
        Request $request,
        CandidateEditRelations $candidateEditRelations,
        CandidatePhotoDecorator $candidatePhotoDecorator,
        VacancyFieldDecorator $vacancyFieldDecorator
    ): Response {
        $photo = $candidate->getPhoto();
        $candidatePhotoDecorator->photoNotNull($candidate);

        $form = $this->createForm(CandidateType::class, $candidate,
            ['constraints' => [new CandidateVacancyCheckExistenceUpdateCandidate()]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Check existence of vacancy
            $vacancyIds = $form->get(self::VACANCY_ENTITY_IN_VIEW)->getData();

            $vacancyFieldDecorator->vacancyField($vacancyIds, $candidate);

            /** @var UploadedFile $file */
            $file = $candidate->getPhoto();
            if ($file !== null) {
                $fileName = $candidatePhotoDecorator->upload($file);
                $candidate->setPhoto($fileName);
            } else {
                $candidate->setPhoto($photo);
            }
            $candidate->setUpdatedDate(new DateTime('now'));

            $this->getDoctrine()->getManager()->flush();
            $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
            $vacancyLinkId = $request->get('vacancyLink');
            if (!empty($vacancyId) || !empty($vacancyLinkId)) {
                if (!empty($vacancyId)) {
                    $id = $candidateEditRelations->candidateFromHunting($candidate, $vacancyId);
                }else {
                    $id = $candidateEditRelations->candidateFromVacancyRecommendation($candidate, $vacancyLinkId);
                }
                return $this->redirectToRoute('vacancy_show_candidates', [
                        'id' => $id
                    ]
                );
            }
            return $this->redirectToRoute(self::CANDIDATE_INDEX, [
                    'id' => $candidate->getId(),
                ]);
            }
        return $this->render('recruiting/candidate/edit.html.twig', [self::CANDIDATE => $candidate,
            'form' => $form->createView(),]);
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
