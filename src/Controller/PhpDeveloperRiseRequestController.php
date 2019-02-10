<?php

namespace App\Controller;

use App\Entity\PhpDeveloperRiseRequest;
use App\Form\PhpDeveloperRiseRequestType;
use App\Repository\PhpDeveloperRiseRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/php/developer/rise/request")
 */
class PhpDeveloperRiseRequestController extends AbstractController
{
    /**
     * @Route("/", name="php_developer_rise_request_index", methods={"GET"})
     */
    public function index(PhpDeveloperRiseRequestRepository $phpDeveloperRiseRequestRepository): Response
    {
        return $this->render('php_developer_rise_request/index.html.twig', [
            'php_developer_rise_requests' => $phpDeveloperRiseRequestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="php_developer_rise_request_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phpDeveloperRiseRequest = new PhpDeveloperRiseRequest();
        $form = $this->createForm(PhpDeveloperRiseRequestType::class, $phpDeveloperRiseRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phpDeveloperRiseRequest);
            $entityManager->flush();

            return $this->redirectToRoute('php_developer_rise_request_index');
        }

        return $this->render('php_developer_rise_request/new.html.twig', [
            'php_developer_rise_request' => $phpDeveloperRiseRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="php_developer_rise_request_show", methods={"GET"})
     */
    public function show(PhpDeveloperRiseRequest $phpDeveloperRiseRequest): Response
    {
        return $this->render('php_developer_rise_request/show.html.twig', [
            'php_developer_rise_request' => $phpDeveloperRiseRequest,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="php_developer_rise_request_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhpDeveloperRiseRequest $phpDeveloperRiseRequest): Response
    {
        $form = $this->createForm(PhpDeveloperRiseRequestType::class, $phpDeveloperRiseRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('php_developer_rise_request_index', [
                'id' => $phpDeveloperRiseRequest->getId(),
            ]);
        }

        return $this->render('php_developer_rise_request/edit.html.twig', [
            'php_developer_rise_request' => $phpDeveloperRiseRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="php_developer_rise_request_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhpDeveloperRiseRequest $phpDeveloperRiseRequest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phpDeveloperRiseRequest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phpDeveloperRiseRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('php_developer_rise_request_index');
    }
}
