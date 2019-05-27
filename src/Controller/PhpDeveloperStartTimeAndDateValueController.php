<?php

namespace App\Controller;

use App\Entity\PhpDeveloperStartTimeAndDateValue;
use App\Form\PhpDeveloperStartTimeAndDateValueType;
use App\Repository\PhpDeveloperStartTimeAndDateValueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/php/developer/start/time/and/date/value")
 */
class PhpDeveloperStartTimeAndDateValueController extends AbstractController
{
    /**
     * @Route("/", name="php_developer_start_time_and_date_value_index", methods={"GET"})
     */
    public function index(PhpDeveloperStartTimeAndDateValueRepository $phpDeveloperStartTimeAndDateValueRepository): Response
    {
        return $this->render('php_developer_start_time_and_date_value/index.html.twig', [
            'php_developer_start_time_and_date_values' => $phpDeveloperStartTimeAndDateValueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="php_developer_start_time_and_date_value_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phpDeveloperStartTimeAndDateValue = new PhpDeveloperStartTimeAndDateValue();
        $form = $this->createForm(PhpDeveloperStartTimeAndDateValueType::class, $phpDeveloperStartTimeAndDateValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phpDeveloperStartTimeAndDateValue);
            $entityManager->flush();

            return $this->redirectToRoute('php_developer_start_time_and_date_value_index');
        }

        return $this->render('php_developer_start_time_and_date_value/new.html.twig', [
            'php_developer_start_time_and_date_value' => $phpDeveloperStartTimeAndDateValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="php_developer_start_time_and_date_value_show", methods={"GET"})
     */
    public function show(PhpDeveloperStartTimeAndDateValue $phpDeveloperStartTimeAndDateValue): Response
    {
        return $this->render('php_developer_start_time_and_date_value/show.html.twig', [
            'php_developer_start_time_and_date_value' => $phpDeveloperStartTimeAndDateValue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="php_developer_start_time_and_date_value_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhpDeveloperStartTimeAndDateValue $phpDeveloperStartTimeAndDateValue): Response
    {
        $form = $this->createForm(PhpDeveloperStartTimeAndDateValueType::class, $phpDeveloperStartTimeAndDateValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('php_developer_start_time_and_date_value_index', [
                'id' => $phpDeveloperStartTimeAndDateValue->getId(),
            ]);
        }

        return $this->render('php_developer_start_time_and_date_value/edit.html.twig', [
            'php_developer_start_time_and_date_value' => $phpDeveloperStartTimeAndDateValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="php_developer_start_time_and_date_value_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhpDeveloperStartTimeAndDateValue $phpDeveloperStartTimeAndDateValue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phpDeveloperStartTimeAndDateValue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phpDeveloperStartTimeAndDateValue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('php_developer_start_time_and_date_value_index');
    }
}
