<?php

namespace App\Controller;

use App\Entity\PhpDeveloperRiseRequest;
use App\Form\PhpDeveloperRiseRequestType;
use App\Repository\PhpDeveloperRiseRequestRepository;
use App\Service\PhpDeveloperRise\Exception\WrongPhpDeveloperConfiguration;
use App\Service\PhpDeveloperRise\PhpDeveloperRiseService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_PHP_MANAGER")
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
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $phpDeveloperRiseRequest = new PhpDeveloperRiseRequest();
        $phpDeveloperRiseRequest->setCreatedDate(New \DateTime());
        $builder = $this->createFormBuilder($phpDeveloperRiseRequest);
        $form = new PhpDeveloperRiseRequestType();
        $form->setManager($this->getUser());
        $form->buildForm($builder, []);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data instanceof PhpDeveloperRiseRequest) {
                try {
                    if ($data->getApproved()) {
                        PhpDeveloperRiseService::processRiseUp($data);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($phpDeveloperRiseRequest);
                    $entityManager->flush();
                } catch (WrongPhpDeveloperConfiguration $e) {
                    $form->addError(New FormError($e->getMessage()));
                }
            }
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
        $builder = $this->createFormBuilder($phpDeveloperRiseRequest);
        $form = new PhpDeveloperRiseRequestType();
        $form->setManager($this->getUser());
        $form->buildForm($builder, []);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data instanceof PhpDeveloperRiseRequest) {
                try {
                    if ($data->getApproved()) {
                        PhpDeveloperRiseService::processRiseUp($data);
                    }
                    $this->getDoctrine()->getManager()->flush();
                } catch (WrongPhpDeveloperConfiguration $e) {
                    $form->addError(New FormError($e->getMessage()));
                }
            }

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
