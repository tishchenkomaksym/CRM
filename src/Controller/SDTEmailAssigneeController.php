<?php

namespace App\Controller;

use App\Entity\SDTEmailAssignee;
use App\Entity\User;
use App\Form\SDTEmailAssigneeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sdt/email/assignee")
 */
class SDTEmailAssigneeController extends AbstractController
{
    /**
     * @Route("/{id}", name="sdt_email_assignee_index", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function index(User $user): Response
    {
        return $this->render('sdt_email_assignee/index.html.twig', [
            'sdt_email_assignees' => $user->getSDTEmailAssignees(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/new/{id}", name="sdt_email_assignee_new", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function new(User $user, Request $request): Response
    {
        $sDTEmailAssignee = new SDTEmailAssignee();
        $form = $this->createForm(SDTEmailAssigneeType::class, $sDTEmailAssignee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sDTEmailAssignee->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sDTEmailAssignee);
            $entityManager->flush();

            return $this->redirectToRoute('sdt_email_assignee_index', ['id' => $user->getId()]);
        }

        return $this->render('sdt_email_assignee/new.html.twig', [
            'sdt_email_assignee' => $sDTEmailAssignee,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/entry/{id}", name="sdt_email_assignee_show", methods={"GET"})
     */
    public function show(SDTEmailAssignee $sDTEmailAssignee): Response
    {
        return $this->render('sdt_email_assignee/show.html.twig', [
            'sdt_email_assignee' => $sDTEmailAssignee,
        ]);
    }

    /**
     * @Route("/{id}/edit/", name="sdt_email_assignee_edit", methods={"GET","POST"})
     * @param Request $request
     * @param SDTEmailAssignee $sDTEmailAssignee
     * @return Response
     */
    public function edit(Request $request, SDTEmailAssignee $sDTEmailAssignee): Response
    {
        $form = $this->createForm(SDTEmailAssigneeType::class, $sDTEmailAssignee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('sdt_email_assignee_index', [
                'id' => $sDTEmailAssignee->getId(),
            ]);
        }

        return $this->render('sdt_email_assignee/edit.html.twig', [
            'sdt_email_assignee' => $sDTEmailAssignee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sdt_email_assignee_delete", methods={"DELETE"})
     * @param Request $request
     * @param SDTEmailAssignee $sDTEmailAssignee
     * @return Response
     */
    public function delete(Request $request, SDTEmailAssignee $sDTEmailAssignee): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sDTEmailAssignee->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sDTEmailAssignee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sdt_email_assignee_index');
    }
}
