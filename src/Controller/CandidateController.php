<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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

        if ($form->isSubmitted() && $form->isValid()) {

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
     * @Route("/{id}", name="candidate_show", methods={"GET"})
     * @param Candidate $candidate
     * @return Response
     */
    public function show(Candidate $candidate): Response
    {
        $vacancy = $candidate->getVacancy();
        return $this->render('candidate/show.html.twig', [
            self::CANDIDATE => $candidate,
            'vacancies' => $vacancy
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
        $photoNull = $candidate->getPhoto() !== null;
        if ($photoNull && false !== stripos($candidate->getPhoto(), '/application/public')) {
            $candidate->setPhoto(
                new File($candidate->getPhoto()));
        } elseif ($photoNull  && false === stripos($candidate->getPhoto(), '/application/public')){
            $candidate->setPhoto(
                new File($this->getParameter(self::UPLOADS_DIRECTORY) . '/' . $candidate->getPhoto())
            );
        }

        $form = $this->createForm(CandidateType::class, $candidate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $candidate->getPhoto();
            if ($file !== null){
                $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
                $file->move($this->getParameter('uploads_directory'), $fileName);
                $candidate->setPhoto($fileName);
            }
            $candidate->setUpdatedDate(new DateTimeImmutable( 'now'));

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute(self::CANDIDATE_INDEX, [
                'id' => $candidate->getId(),
            ]);
        }

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);
        $this->getDoctrine()->getManager()->flush();


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
