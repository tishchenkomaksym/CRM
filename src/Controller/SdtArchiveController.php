<?php

namespace App\Controller;

use App\Entity\SdtArchive;
use App\Form\SdtArchiveType;
use App\Repository\SdtArchiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sdt/archive")
 */
class SdtArchiveController extends AbstractController
{
    /**
     * @Route("/", name="sdt_archive_index", methods={"GET"})
     */
    public function index(SdtArchiveRepository $sdtArchiveRepository): Response
    {
        return $this->render('sdt_archive/index.html.twig', [
            'sdt_archives' => $sdtArchiveRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sdt_archive_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sdtArchive = new SdtArchive();
        $form = $this->createForm(SdtArchiveType::class, $sdtArchive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sdtArchive);
            $entityManager->flush();

            return $this->redirectToRoute('sdt_archive_index');
        }

        return $this->render('sdt_archive/new.html.twig', [
            'sdt_archive' => $sdtArchive,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sdt_archive_show", methods={"GET"})
     */
    public function show(SdtArchive $sdtArchive): Response
    {
        return $this->render('sdt_archive/show.html.twig', [
            'sdt_archive' => $sdtArchive,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sdt_archive_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SdtArchive $sdtArchive): Response
    {
        $form = $this->createForm(SdtArchiveType::class, $sdtArchive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sdt_archive_index', [
                'id' => $sdtArchive->getId(),
            ]);
        }

        return $this->render('sdt_archive/edit.html.twig', [
            'sdt_archive' => $sdtArchive,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sdt_archive_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SdtArchive $sdtArchive): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sdtArchive->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sdtArchive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sdt_archive_index');
    }
}
