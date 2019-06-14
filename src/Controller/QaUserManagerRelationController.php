<?php

namespace App\Controller;

use App\Entity\QaUserManagerRelation;
use App\Entity\User;
use App\Form\QaUserManagerRelationType;
use App\Service\QaUserManagerRelation\Create\QaUserManagerRelationCreateStrategy;
use App\Service\QaUserManagerRelation\QaUserManagerRelationCRUDContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/qa/user/manager/relation")
 */
class QaUserManagerRelationController extends AbstractController
{
    /**
     * @Route("/new/{id}", name="qa_user_manager_relation_new", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @param QaUserManagerRelationCreateStrategy $createStrategy
     * @return Response
     */
    public function new(
        User $user,
        Request $request,
        QaUserManagerRelationCreateStrategy $createStrategy
    ): Response
    {
        $qaUserManagerRelation = new QaUserManagerRelation();
        $form = $this->createForm(QaUserManagerRelationType::class, $qaUserManagerRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            (new QaUserManagerRelationCRUDContext($createStrategy))->makeAction($qaUserManagerRelation,$user);
            return $this->redirectToRoute(UserController::ROUTE_USER_SHOW, ['id' => $user->getId()]);
        }

        return $this->render(
            'qa_user_manager_relation/new.html.twig',
            [
                'contextUser' => $user,
                'qa_user_manager_relation' => $qaUserManagerRelation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="qa_user_manager_relation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QaUserManagerRelation $qaUserManagerRelation): Response
    {
        $form = $this->createForm(QaUserManagerRelationType::class, $qaUserManagerRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getQaManager()->flush();
            $qa = $qaUserManagerRelation->getQaUser();
            if ($qa !== null) {
                return $this->redirectToRoute(
                    'user_show',
                    [
                        'id' => $qa->getId(),
                    ]
                );
            }
            return $this->redirectToRoute(
                'default'
            );
        }

        return $this->render(
            'qa_user_manager_relation/edit.html.twig',
            [
                'qa_user_manager_relation' => $qaUserManagerRelation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="qa_user_manager_relation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QaUserManagerRelation $qaUserManagerRelation): Response
    {
        $qa = $qaUserManagerRelation->getQaUser();

        if ($this->isCsrfTokenValid(
            'delete' . $qaUserManagerRelation->getId(),
            $request->request->get('_token')
        )) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($qaUserManagerRelation);
            $entityManager->flush();
        }
        if ($qa !== null) {
            $id = $qa->getId();
        } else {
            $this->redirectToRoute('default');
        }
        return $this->redirectToRoute('user_show', ['id' => $id]);
    }
}
