<?php

namespace App\Controller;

use App\Entity\MonthlySdt;
use App\Entity\User;
use App\Form\MonthlySdtType;
use App\Repository\MonthlySdtRepository;
use App\Repository\UserRepository;
use App\Service\MonthlySdt\Builder\PhpDeveloperMonthlySDTBuilder;
use App\Service\MonthlySdt\MonthlySdtService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $monthlySdts = $user->getMonthlySdts();
        }
        return $this->render(
            'monthly_sdt/index.html.twig',
            [
                'monthly_sdts' => $monthlySdts,
            ]
        );
    }

    /**
     * @IsGranted("ROLE_MANAGE_MONTHLY_SDT")
     * @Route("/view/all", name="monthly_sdt_view_all", methods={"GET"})
     */
    public function viewAll(MonthlySdtRepository $repository): Response
    {
        return $this->render(
            'monthly_sdt/index.html.twig',
            [
                'monthly_sdts' => $repository->findAll(),
            ]
        );
    }

    /**
     * @IsGranted("ROLE_MANAGE_MONTHLY_SDT")
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
     * @IsGranted("ROLE_MANAGE_MONTHLY_SDT")
     * @Route("/generate", name="monthly_sdt_generate", methods={"GET","POST"})
     * @throws \Exception
     */
    public function generate(UserRepository $userRepository, MonthlySdtRepository $monthlySdtRepository): Response
    {
        /** @var User[] $users */
        $users = $userRepository->findAll();
        $entityManager = $this->getDoctrine()->getManager();

        $now = new \DateTime();
        if (MonthlySdtService::isAllowedToGenerate($now, $monthlySdtRepository)) {
            foreach ($users as $user) {
                $monthlySdt = PhpDeveloperMonthlySDTBuilder::build($user, $now);
                $entityManager->persist($monthlySdt);
            }
        }

        $entityManager->flush();
        $entityManager->clear();
        return $this->redirectToRoute('monthly_sdt_view_all');
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
     * @IsGranted("ROLE_MANAGE_MONTHLY_SDT")
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
