<?php

namespace App\Controller;

use App\Entity\PhpDeveloperLevelTestPassed;
use App\Entity\User;
use App\Form\PhpDeveloperLevelTestPassedType;
use App\Repository\PhpDeveloperLevelTestPassedRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ACCOUNT_MANAGER")
 * @Route("/php/developer/level/test/passed")
 */
class PhpDeveloperLevelTestPassedController extends AbstractController
{
    /**
     * @Route("/index/{id}", name="php_developer_level_test_passed_index", methods={"GET"})
     * @param User $user
     * @param PhpDeveloperLevelTestPassedRepository $phpDeveloperLevelTestPassedRepository
     * @return Response
     */
    public function index(
        User $user,
        PhpDeveloperLevelTestPassedRepository $phpDeveloperLevelTestPassedRepository
    ): Response {
        return $this->render(
            'php_developer_level_test_passed/index.html.twig',
            [
                'user' => $user,
                'alreadyPassed' => $phpDeveloperLevelTestPassedRepository->getByUser($user),
            ]
        );
    }

    /**
     * @Route("/{id}/new", name="php_developer_level_test_passed_new", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function new(User $user, Request $request): Response
    {
        $phpDeveloperLevelTestPassed = new PhpDeveloperLevelTestPassed();
        $phpDeveloperLevelTestPassed->setUser($user);
        $form = $this->createForm(
            PhpDeveloperLevelTestPassedType::class, $phpDeveloperLevelTestPassed
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phpDeveloperLevelTestPassed);
            $entityManager->flush();

            return $this->redirectToRoute('php_developer_level_test_passed_index', ['id' => $user->getId()]);
        }

        return $this->render(
            'php_developer_level_test_passed/new.html.twig', [
                                                               'user' => $user,
                                                               'form' => $form->createView(),
                                                           ]
        );
    }

    /**
     * @Route("/{id}", name="php_developer_level_test_passed_show", methods={"GET"})
     * @param PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed
     * @return Response
     */
    public function show(PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed): Response
    {
        return $this->render(
            'php_developer_level_test_passed/show.html.twig', [
                                                                'user' => $phpDeveloperLevelTestPassed->getUser(),
                                                                'php_developer_level_test_passed' => $phpDeveloperLevelTestPassed,
                                                            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="php_developer_level_test_passed_edit", methods={"GET","POST"})
     * @param Request $request
     * @param PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed
     * @return Response
     */
    public function edit(Request $request, PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed): Response
    {
        $form = $this->createForm(PhpDeveloperLevelTestPassedType::class, $phpDeveloperLevelTestPassed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'php_developer_level_test_passed_index', [
                                                           'id' => $phpDeveloperLevelTestPassed->getId(),
                                                       ]
            );
        }

        return $this->render(
            'php_developer_level_test_passed/edit.html.twig', [
                                                                'user' => $phpDeveloperLevelTestPassed->getUser(),
                                                                'php_developer_level_test_passed' => $phpDeveloperLevelTestPassed,
                                                                'form' => $form->createView(),
                                                            ]
        );
    }

    /**
     * @Route("/{id}", name="php_developer_level_test_passed_delete", methods={"DELETE"})
     * @param Request $request
     * @param PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed
     * @return Response
     */
    public function delete(Request $request, PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed): Response
    {
        $user = $phpDeveloperLevelTestPassed->getUser();

        if ($this->isCsrfTokenValid(
            'delete' . $phpDeveloperLevelTestPassed->getId(), $request->request->get('_token')
        )) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phpDeveloperLevelTestPassed);
            $entityManager->flush();
        }
        if ($user !== null) {
            $userId = $user->getId();
            return $this->redirectToRoute('php_developer_level_test_passed_index', ['id' => $userId]);
        }
        return $this->redirectToRoute('default');
    }
}
