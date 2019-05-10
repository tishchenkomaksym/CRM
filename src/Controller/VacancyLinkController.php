<?php

namespace App\Controller;

use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use App\Form\VacancyLinkType;
use App\Repository\VacancyLinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vacancy/link")
 */
class VacancyLinkController extends AbstractController
{

    public const VACANCY_LINK = 'vacancy_link';

    public const VACANCY_LINK_INDEX = 'vacancy_link_index';

    /**
     * @Route("/{id}", name="vacancy_link_index", methods={"GET"})
     * @param Vacancy $vacancy
     * @param VacancyLinkRepository $vacancyLinkRepository
     * @return Response
     */
    public function index(Vacancy $vacancy, VacancyLinkRepository $vacancyLinkRepository): Response
    {
        return $this->render('vacancy_link/index.html.twig', [
            'vacancy_links' => $vacancyLinkRepository->findAll(),
            'vacancy' => $vacancy
        ]);
    }

    /**
     * @Route("/new/{id}", name="vacancy_link_new", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @return Response
     */
    public function new(Vacancy $vacancy,Request $request): Response
    {
        $vacancyLink = new VacancyLink();
        $form = $this->createForm(VacancyLinkType::class, $vacancyLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $vacancyLink->setVacancy($vacancy);
            $vacancy->setStatus('Search for a candidate(s) have been started');
            $entityManager->persist($vacancyLink);
            $entityManager->flush();

            return $this->redirectToRoute(self::VACANCY_LINK_INDEX,[
            'id' => $vacancy->getId(),
            ]);
        }

        return $this->render('vacancy_link/new.html.twig', [
            self::VACANCY_LINK => $vacancyLink,
            'form' => $form->createView(),
            'vacancy' => $vacancy
        ]);
    }

    /**
     * @Route("/show/{id}", name="vacancy_link_show", methods={"GET"})
     * @param VacancyLink $vacancyLink
     * @return Response
     */
    public function show(VacancyLink $vacancyLink): Response
    {
        return $this->render('vacancy_link/show.html.twig', [
            self::VACANCY_LINK => $vacancyLink,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vacancy_link_edit", methods={"GET","POST"})
     * @param Request $request
     * @param VacancyLink $vacancyLink
     * @return Response
     */
    public function edit(Request $request, VacancyLink $vacancyLink): Response
    {
        $form = $this->createForm(VacancyLinkType::class, $vacancyLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(self::VACANCY_LINK_INDEX, [
                'id' => $vacancyLink->getId(),
            ]);
        }

        return $this->render('vacancy_link/edit.html.twig', [
            self::VACANCY_LINK => $vacancyLink,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vacancy_link_delete", methods={"DELETE"})
     * @param Request $request
     * @param VacancyLink $vacancyLink
     * @return Response
     */
    public function delete(Request $request, VacancyLink $vacancyLink): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vacancyLink->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vacancyLink);
            $entityManager->flush();
        }

        return $this->redirectToRoute(self::VACANCY_LINK_INDEX);
    }
}
