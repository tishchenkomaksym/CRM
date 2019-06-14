<?php

namespace App\Controller;

use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\QaUserManagerRelation;
use App\Entity\User;
use App\Form\PhpDeveloperManagerRelationType;
use App\Service\PhpDeveloperManagerRelation\Create\PhpDeveloperManagerRelationCreateStrategy;
use App\Service\PhpDeveloperManagerRelation\PhpDeveloperManagerRelationCRUDContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/php/developer/manager/relation")
 */
class QaUserManagerRelationController extends AbstractController
{
    /**
     * @Route("/new/{id}", name="qa_user__manager_relation_new", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @param PhpDeveloperManagerRelationCreateStrategy $createStrategy
     * @return Response
     */
    public function new(
        User $user,
        Request $request,
        PhpDeveloperManagerRelationCreateStrategy $createStrategy
    ): Response
    {
        $phpDeveloperManagerRelation = new QaUserManagerRelation();
        $form = $this->createForm(PhpDeveloperManagerRelationType::class, $phpDeveloperManagerRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            (new PhpDeveloperManagerRelationCRUDContext($createStrategy))->makeAction($phpDeveloperManagerRelation,$user);
            return $this->redirectToRoute(UserController::ROUTE_USER_SHOW, ['id' => $user->getId()]);
        }

        return $this->render(
            'php_developer_manager_relation/new.html.twig',
            [
                'contextUser' => $user,
                'php_developer_manager_relation' => $phpDeveloperManagerRelation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="php_developer_manager_relation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhpDeveloperManagerRelation $phpDeveloperManagerRelation): Response
    {
        $form = $this->createForm(PhpDeveloperManagerRelationType::class, $phpDeveloperManagerRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $developer = $phpDeveloperManagerRelation->getPhpDeveloper();
            if ($developer !== null) {
                return $this->redirectToRoute(
                    'user_show',
                    [
                        'id' => $developer->getId(),
                    ]
                );
            }
            return $this->redirectToRoute(
                'default'
            );
        }

        return $this->render(
            'php_developer_manager_relation/edit.html.twig',
            [
                'php_developer_manager_relation' => $phpDeveloperManagerRelation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="php_developer_manager_relation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhpDeveloperManagerRelation $phpDeveloperManagerRelation): Response
    {
        $developer = $phpDeveloperManagerRelation->getPhpDeveloper();

        if ($this->isCsrfTokenValid(
            'delete' . $phpDeveloperManagerRelation->getId(),
            $request->request->get('_token')
        )) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phpDeveloperManagerRelation);
            $entityManager->flush();
        }
        if ($developer !== null) {
            $id = $developer->getId();
        } else {
            $this->redirectToRoute('default');
        }
        return $this->redirectToRoute('user_show', ['id' => $id]);
    }
}
