<?php

namespace App\Controller;

use App\Entity\Vacancy;
use App\Form\VacancyType;
use App\Repository\VacancyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vacancy")
 */
class VacancyController extends AbstractController
{
    /**
     * @Route("/", name="vacancy_index", methods={"GET"})
     */
    public function index(VacancyRepository $vacancyRepository): Response
    {
        return $this->render('vacancy/index.html.twig', [
            'vacancies' => $vacancyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/result", name="vacancy_result", methods={"GET"})
     */
    public function result()
    {
        return $this->render('vacancy/result.html.twig', [
            'controller_name' => 'ResultController',
        ]);
    }

    /**
     * @Route("/new", name="vacancy_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vacancy = new Vacancy();
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vacancy);
            $entityManager->flush();

            return $this->redirectToRoute('vacancy_result');
        }

        return $this->render('vacancy/new.html.twig', [
            'vacancy' => $vacancy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vacancy_show", methods={"GET"})
     */
    public function show(Vacancy $vacancy): Response
    {
        return $this->render('vacancy/show.html.twig', [
            'vacancy' => $vacancy,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vacancy_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vacancy $vacancy): Response
    {
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vacancy_edit', [
                'id' => $vacancy->getId(),
            ]);
        }

        return $this->render('vacancy/edit.html.twig', [
            'vacancy' => $vacancy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vacancy_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vacancy $vacancy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vacancy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vacancy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vacancy_index');
    }


}
