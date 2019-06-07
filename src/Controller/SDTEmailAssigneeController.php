<?php

namespace App\Controller;

use App\Entity\SDTEmailAssignee;
use App\Entity\User;
use App\Form\SDTEmailAssigneeType;
use App\Repository\SDTEmailAssigneeRepository;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sdt/email/assignee")
 */
class SDTEmailAssigneeController extends AbstractController
{
    public const SDT_EMAIL_ASSIGNEE = 'sdt_email_assignee';

    public const SDT_EMAIL_ASSIGNEE_INDEX = 'sdt_email_assignee_index';

    /**
     * @Route("/{id}", name="sdt_email_assignee_index", methods={"GET"})
     * @param User $user
     * @param SDTEmailAssigneeRepository $SDTEmailAssigneeRepository
     * @return Response
     */
    public function index(User $user, SDTEmailAssigneeRepository $SDTEmailAssigneeRepository): Response
    {
        return $this->render('sdt_email_assignee/index.html.twig', [
            'sdt_email_assignees' => $SDTEmailAssigneeRepository->findBy(['user' => $user->getId()]),
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

            return $this->redirectToRoute(self::SDT_EMAIL_ASSIGNEE_INDEX, ['id' => $user->getId()]);
        }

        return $this->render('sdt_email_assignee/new.html.twig', [
            self::SDT_EMAIL_ASSIGNEE => $sDTEmailAssignee,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/entry/{id}", name="sdt_email_assignee_show", methods={"GET"})
     * @param SDTEmailAssignee $sDTEmailAssignee
     * @return Response
     */
    public function show(SDTEmailAssignee $sDTEmailAssignee): Response
    {
        return $this->render('sdt_email_assignee/show.html.twig', [
            self::SDT_EMAIL_ASSIGNEE => $sDTEmailAssignee,
        ]);
    }

    /**
     * @Route("/{id}/edit/", name="sdt_email_assignee_edit", methods={"GET","POST"})
     * @param Request $request
     * @param SDTEmailAssignee $sDTEmailAssignee
     * @return Response
     * @throws NoDataException
     */
    public function edit(SDTEmailAssignee $sDTEmailAssignee, Request $request): Response
    {
        $form = $this->createForm(SDTEmailAssigneeType::class, $sDTEmailAssignee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($sDTEmailAssignee->getUser() === null){
                throw new NoDataException('User not found');
            }
            return $this->redirectToRoute(self::SDT_EMAIL_ASSIGNEE_INDEX, [
                'id' => $sDTEmailAssignee->getUser()->getId(),
            ]);
        }

        return $this->render('sdt_email_assignee/edit.html.twig', [
            self::SDT_EMAIL_ASSIGNEE => $sDTEmailAssignee,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="sdt_email_assignee_delete", methods={"DELETE"})
//     * @param Request $request
//     * @param SDTEmailAssignee $sDTEmailAssignee
//     * @return Response
//     * @throws NoDataException
//     */
//    public function delete(Request $request, SDTEmailAssignee $sDTEmailAssignee): Response
//    {
//        if ($this->isCsrfTokenValid('delete' . $sDTEmailAssignee->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($sDTEmailAssignee);
//            $entityManager->flush();
//        }
//
//        if ($sDTEmailAssignee->getUser() === null){
//            throw new NoDataException('User not found');
//        }
//        return $this->redirectToRoute(self::SDT_EMAIL_ASSIGNEE_INDEX, [
//            'id' => $sDTEmailAssignee->getUser()->getId(),
//        ]);
//    }
}
