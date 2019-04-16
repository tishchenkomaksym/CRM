<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPhpDeveloperLevelRelation;
use App\Form\UserPhpDeveloperLevelRelationType;
use App\Repository\UserPhpDeveloperLevelRelationRepository;
use App\Service\User\PhpDeveloperLevel\PhpDeveloperLevelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/php/developer/level/relation")
 */
class UserPhpDeveloperLevelRelationController extends AbstractController
{
    /**
     * @Route("/{id}", name="user_php_developer_level_relation_index", methods={"GET"})
     */
    public function index(
        User $user,
        UserPhpDeveloperLevelRelationRepository $userPhpDeveloperLevelRelationRepository
    ): Response {
        return $this->render(
            'user_php_developer_level_relation/index.html.twig',
            [
                'contextUser' => $user,
                'user_php_developer_level_relations' => $userPhpDeveloperLevelRelationRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new/{id}", name="user_php_developer_level_relation_new", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(User $user, Request $request): Response
    {
        $userPhpDeveloperLevelRelation = new UserPhpDeveloperLevelRelation();
        $form = $this->createForm(UserPhpDeveloperLevelRelationType::class, $userPhpDeveloperLevelRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = PhpDeveloperLevelService::resetPhpDeveloperRoles($user);
            $userPhpDeveloperLevelRelation->setCreateDate(new \DateTimeImmutable());
            $userPhpDeveloperLevelRelation->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userPhpDeveloperLevelRelation);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render(
            'user_php_developer_level_relation/new.html.twig',
            [
                'contextUser' => $user,
                'user_php_developer_level_relation' => $userPhpDeveloperLevelRelation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="user_php_developer_level_relation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPhpDeveloperLevelRelation $userPhpDeveloperLevelRelation): Response
    {
        $form = $this->createForm(UserPhpDeveloperLevelRelationType::class, $userPhpDeveloperLevelRelation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'user_php_developer_level_relation_index',
                [
                    'id' => $userPhpDeveloperLevelRelation->getId(),
                ]
            );
        }

        return $this->render(
            'user_php_developer_level_relation/edit.html.twig',
            [
                'user_php_developer_level_relation' => $userPhpDeveloperLevelRelation,
                'form' => $form->createView(),
            ]
        );
    }

//    /**
//     * @Route("/{id}", name="user_php_developer_level_relation_show", methods={"GET"})
//     */
//    public function show(UserPhpDeveloperLevelRelation $userPhpDeveloperLevelRelation): Response
//    {
//        return $this->render(
//            'user_php_developer_level_relation/show.html.twig',
//            [
//                'user_php_developer_level_relation' => $userPhpDeveloperLevelRelation,
//            ]
//        );
//    }

    /**
     * @Route("/{id}", name="user_php_developer_level_relation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserPhpDeveloperLevelRelation $userPhpDeveloperLevelRelation): Response
    {
        if ($this->isCsrfTokenValid(
            'delete' . $userPhpDeveloperLevelRelation->getId(),
            $request->request->get('_token')
        )) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userPhpDeveloperLevelRelation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_php_developer_level_relation_index');
    }
}
