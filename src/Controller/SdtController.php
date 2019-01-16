<?php

namespace App\Controller;

use App\Entity\Sdt;
use App\Entity\User;
use App\Form\SdtType;
use App\Repository\SdtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sdt")
 */
class SdtController extends AbstractController
{
    /**
     * @Route("/", name="sdt_index", methods={"GET"})
     */
    public function index(SdtRepository $sdtRepository): Response
    {
        $sdtList = $sdtRepository->findAll();
        $eventsInCalendar = [];
        foreach ($sdtList as $sdt) {
            $createDate = $sdt->getCreateDate();
            if (!empty($createDate)) {
                $eventsInCalendar[] = [
                    'title' => 'Sdt',
                    'start' => $createDate->format('Y-m-d'),
                    'url' => $this->generateUrl('sdt_show', ['id' => $sdt->getId()]),
                    'end' => date_modify($createDate, '+' . $sdt->getCount() . ' weekdays')->format('Y-m-d')
                ];
            }
        }
        $calendarEvents = json_encode(['events' => $eventsInCalendar]);
        return $this->render(
            'sdt/index.html.twig',
            [
                'sdts' => $sdtRepository->findAll(),
                'calendarEvents' => $calendarEvents
            ]
        );
    }

    /**
     * @Route("/new", name="sdt_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sdt = new Sdt();
        $sdt->setUser($this->getUser());
        $form = $this->createFormBuilder($sdt)
                     ->add('count', IntegerType::class)
                     ->add('createDate', DateType::class,['widget'=>'single_text'] )
                     ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sdt);
            $entityManager->flush();

            return $this->redirectToRoute('sdt_index');
        }

        return $this->render(
            'sdt/new.html.twig',
            [
                'sdt' => $sdt,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="sdt_show", methods={"GET"})
     */
    public function show(Sdt $sdt): Response
    {
        return $this->render(
            'sdt/show.html.twig',
            [
                'sdt' => $sdt,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="sdt_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sdt $sdt): Response
    {
        $form = $this->createFormBuilder($sdt)
                     ->add('count', IntegerType::class)
                     ->add('createDate', DateType::class,['widget'=>'single_text'] )
                     ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'sdt_index',
                [
                    'id' => $sdt->getId(),
                ]
            );
        }

        return $this->render(
            'sdt/edit.html.twig',
            [
                'sdt' => $sdt,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="sdt_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sdt $sdt): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sdt->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sdt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sdt_index');
    }
}
