<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vacancy;
use App\Entity\VacancyViewerUser;
use App\Form\RecruiterType;
use App\Form\VacancyType;
use App\Form\VacancyTypeDenied;
use App\Form\ViewerType;
use App\Repository\UserRepository;
use App\Repository\VacancyRepository;
use App\Service\Vacancy\CreateForHrManager\NewVacancyMessageBuilderForHrManager;
use App\Service\Vacancy\CreateForManager\NewVacancyMessageBuilderForManager;
use App\Service\Vacancy\CreateVacancy\NewVacancyMessageBuilder;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
  * @IsGranted("ROLE_VACANCY_VIEWER_USER")
 * @Route("/vacancy")
 */

class VacancyController extends AbstractController
{

    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

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
     * @Route("/approve/{id}", name="approved", methods={"GET"})
     * @throws \Exception
     */
    public function approve(UserRepository $userRepository, Vacancy $vacancy, Swift_Mailer $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vacancy->setIsApproved(true);

        $vacancy->setApproveDate(new \DateTimeImmutable($time = 'now'));
        $entityManager->persist($vacancy);
        $entityManager->flush();

        $messageBuilder = new NewVacancyMessageBuilderForManager(
            $vacancy, $this->environment
        );

        $messageBuilderHr = new NewVacancyMessageBuilderForHrManager(
            $userRepository, $vacancy, $this->environment
        );
        
        $mailer->send($messageBuilder->build());
        $mailer->send($messageBuilderHr->build());

        return $this->render('vacancy/approved.html.twig', [
            'vacancy' => $vacancy
        ]);
    }

    /**
     * @Route("/deny/{id}", name="denied", methods={"GET","POST"})
     */
    public function deny(Vacancy $vacancy, Request $request): Response
    {

        $form = $this->createForm(VacancyTypeDenied::class, $vacancy);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $vacancy->setIsApproved(false);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('vacancy_denied', [
                'id' => $vacancy->getId(),
            ]);
        }
        return $this->render('vacancy/denied.html.twig', [
            'form' => $form->createView(),
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
     * @Route("/denied/{id}", name="vacancy_denied", methods={"GET"})
     *  @throws \Exception
     */
    public function resultDenied(Vacancy $vacancy, Swift_Mailer $mailer)
    {
        $messageBuilder = new NewVacancyMessageBuilderForManager(
            $vacancy, $this->environment
        );
        $mailer->send($messageBuilder->build());

        return $this->render('vacancy/deniedResult.html.twig', [
            'vacancy' => $vacancy
        ]);
    }

    /**
     * @Route("/new", name="vacancy_new", methods={"GET","POST"})
     * @throws \Exception
     */
    public function new(Request $request, Swift_Mailer $mailer): Response
    {

        $vacancy = new Vacancy();
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);
        $vacancy->setCreatedBy($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $vacancy->setCreatedAt(new \DateTimeImmutable($time = 'now'));
            $entityManager->persist($vacancy);
            $entityManager->flush();

            $messageBuilder = new NewVacancyMessageBuilder(
                $vacancy, $this->environment
            );
            $mailer->send($messageBuilder->build());

            return $this->redirectToRoute('vacancy_result');
        }


        return $this->render('vacancy/new.html.twig', [
            'vacancy' => $vacancy,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vacancy_show", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @return Response
     */
    public function show(Vacancy $vacancy, Request $request): Response
    {
        $viewerUser = new VacancyViewerUser();
        $formUser = $this->createForm(ViewerType::class, $viewerUser);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $entityManagerUser = $this->getDoctrine()->getManager();
            $viewerUser->setPermissionUser($this->getUser());
            $entityManagerUser->persist($viewerUser);
            $entityManagerUser->flush();
        }

        $form = $this->createForm(RecruiterType::class, $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vacancy);
            $entityManager->flush();
        }
        return $this->render('vacancy/show.html.twig', [
            'vacancy' => $vacancy,
            'form' => $form->createView(),
            'formUser' => $formUser->createView()
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
     * @param Request $request
     * @param Vacancy $vacancy
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
