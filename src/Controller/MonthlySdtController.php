<?php

namespace App\Controller;

use App\Entity\MonthlySdt;
use App\Form\MonthlySdtType;
use App\Repository\MonthlySdtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/monthly/sdt")
 */
class MonthlySdtController extends AbstractController
{
    /**
     * @Route("/", name="monthly_sdt_index", methods={"GET"})
     */
    public function index(MonthlySdtRepository $monthlySdtRepository): Response
    {
        return $this->render(
            'monthly_sdt/index.html.twig',
            [
                'monthly_sdts' => $monthlySdtRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="monthly_sdt_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $monthlySdt = new MonthlySdt();
        $form = $this->createForm(MonthlySdtType::class, $monthlySdt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($monthlySdt);
            $entityManager->flush();

            return $this->redirectToRoute('monthly_sdt_index');
        }

        return $this->render(
            'monthly_sdt/new.html.twig',
            [
                'monthly_sdt' => $monthlySdt,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="monthly_sdt_show", methods={"GET"})
     */
    public function show(MonthlySdt $monthlySdt): Response
    {
        return $this->render(
            'monthly_sdt/show.html.twig',
            [
                'monthly_sdt' => $monthlySdt,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="monthly_sdt_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MonthlySdt $monthlySdt): Response
    {
        $form = $this->createForm(MonthlySdtType::class, $monthlySdt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'monthly_sdt_index',
                [
                    'id' => $monthlySdt->getId(),
                ]
            );
        }

        return $this->render(
            'monthly_sdt/edit.html.twig',
            [
                'monthly_sdt' => $monthlySdt,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="monthly_sdt_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MonthlySdt $monthlySdt): Response
    {
        if ($this->isCsrfTokenValid('delete' . $monthlySdt->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($monthlySdt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('monthly_sdt_index');
    }
}
