<?php

namespace App\Controller;

use App\Calendar\CalendarEventItemCollection;
use App\Calendar\HolidayCalendarEventItemBuilder;
use App\Calendar\SdtCalendarEventItemBuilder;
use App\Data\Sdt\Mail\Adapter\DeleteSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\EditSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\NewSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\DeleteSdtMailData;
use App\Data\Sdt\Mail\EditSdtMailData;
use App\Data\Sdt\Mail\NewSdtMailData;
use App\Entity\Sdt;
use App\Repository\SdtRepository;
use App\Service\HolidayService;
use App\Service\UserInformationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'leftSdt' => $userInformationService->getSdtLeft($sdtCollection, $this->getUser())
            ]
        );
    }

    /**
     * @Route("/new", name="sdt_new", methods={"GET","POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @return Response
     * @throws \App\Data\Sdt\Mail\Adapter\NoDateException
     */
    public function new(Request $request, \Swift_Mailer $mailer, HolidayService $holidayService): Response
    {
        $sdt = new Sdt();
        $sdt->setUser($this->getUser());
        $form = $this->createFormBuilder($sdt)
                     ->add('count', IntegerType::class)
                     ->add('createDate', DateType::class, ['widget' => 'single_text'])
                     ->add('acting', TextType::class)
                     ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->sendNewSdtEmail($mailer, NewSdtMailFromSdtAdapter::getNewSdtMail($sdt, $holidayService));

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
     * @param Sdt $sdt
     * @return Response
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
     * @param Request $request
     * @param Sdt $sdt
     * @param \Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @return Response
     * @throws \App\Data\Sdt\Mail\Adapter\NoDateException
     */
    public function edit(Request $request, Sdt $sdt, \Swift_Mailer $mailer, HolidayService $holidayService): Response
    {
        $form = $this->createFormBuilder($sdt)
                     ->add('count', IntegerType::class)
                     ->add('createDate', DateType::class, ['widget' => 'single_text'])
                     ->add('acting', TextType::class, ['label' => 'Acting person'])
                     ->getForm();
        $oldFromDate = $sdt->getCreateDate();
        $oldCount = $sdt->getCount();
        $form->handleRequest($request);

        if ($oldFromDate !== null && $form->isSubmitted() && $form->isValid()) {
            $this->sendEditSdtEmail(
                $mailer,
                EditSdtMailFromSdtAdapter::getEditSdtMail($sdt, $oldFromDate, $oldCount, $holidayService)
            );
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
     * @param Request $request
     * @param Sdt $sdt
     * @param \Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @return Response
     * @throws \App\Data\Sdt\Mail\Adapter\NoDateException
     */
    public function delete(Request $request, Sdt $sdt, \Swift_Mailer $mailer, HolidayService $holidayService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sdt->getId(), $request->request->get('_token'))) {
            $this->sendDeleteSdtEmail($mailer, DeleteSdtMailFromSdtAdapter::getNewSdtMail($sdt, $holidayService));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sdt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sdt_index');
    }

    private function sendNewSdtEmail(\Swift_Mailer $mailer, NewSdtMailData $mailData): int
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($this->getUser()->getEmail())
            ->setTo($mailData->getToEmails())
            ->setBody(
                $this->renderView(
                    'emails/sdt/newSdt.twig',
                    [
                        'fromDate' => $mailData->getFromDate(),
                        'toDate' => $mailData->getToDate(),
                        'daysCount' => $mailData->getDaysCount(),
                        'actingPeople' => $mailData->getActingPeople(),
                    ]
                ),
                'text/html'
            );

        return $mailer->send($message);
    }

    private function sendEditSdtEmail(\Swift_Mailer $mailer, EditSdtMailData $mailData): int
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($this->getUser()->getEmail())
            ->setTo($mailData->getToEmails())
            ->setBody(
                $this->renderView(
                    'emails/sdt/editSdt.twig',
                    [
                        'oldFromDate' => $mailData->getOldFromDate(),
                        'oldToDate' => $mailData->getOldToDate(),
                        'newFromDate' => $mailData->getOldFromDate(),
                        'newToDate' => $mailData->getNewToDate(),
                        'actingPeople' => $mailData->getActingPeople(),
                        'daysCount' => $mailData->getDaysCount(),
                    ]
                ),
                'text/html'
            );

        return $mailer->send($message);
    }

    private function sendDeleteSdtEmail(\Swift_Mailer $mailer, DeleteSdtMailData $mailData): int
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($this->getUser()->getEmail())
            ->setTo(
                $mailData->getToEmails()
            )
            ->setBody(
                $this->renderView(
                    'emails/sdt/deleteSdt.twig',
                    [
                        'fromDate' => $mailData->getFromDate(),
                        'toDate' => $mailData->getToDate(),
                        'daysCount' => $mailData->getDaysCount()
                    ]
                ),
                'text/html'
            );

        return $mailer->send($message);
    }
}
