<?php

namespace App\Controller;

use App\Calendar\CalendarEventItemCollection;
use App\Calendar\HolidayCalendarEventItemBuilder;
use App\Calendar\SdtCalendarEventItemBuilder;
use App\Entity\Sdt;
use App\Repository\SdtRepository;
use App\Service\HolidayService;
use App\Service\UserInformationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
     * @param SdtRepository $sdtRepository
     * @param HolidayService $holidayService
     * @param UserInformationService $userInformationService
     * @return Response
     */
    public function index(
        SdtRepository $sdtRepository,
        HolidayService $holidayService,
        UserInformationService $userInformationService
    ): Response
    {
        $sdtCollection = $userInformationService
            ->getAllUserSdt($sdtRepository, $this->getUser()->getId());

        $calendarEventItemCollection = new CalendarEventItemCollection();
        foreach ($sdtCollection->getItems() as $sdt) {
            $calendarEventItemCollection->add(
                (new SdtCalendarEventItemBuilder(
                    $sdt,
                    $this->container->get('router'),
                    $this->getUser(),
                    $holidayService
                ))->build()
            );
        }

        foreach ($holidayService->getHolidays() as $holiday) {
            $calendarEventItemCollection->add(
                (new HolidayCalendarEventItemBuilder(
                    $holiday,
                    $this->container->get('router')
                ))->build()
            );
        }

        return $this->render(
            'sdt/index.html.twig',
            [
                'sdts' => $sdtCollection->getItems(),
                'calendarEvents' => $calendarEventItemCollection->toJson(),
                'leftSdt' => $userInformationService->getSdtLeft($sdtCollection)
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
                     ->add('createDate', DateType::class, ['widget' => 'single_text'])
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
                     ->add('createDate', DateType::class, ['widget' => 'single_text'])
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
