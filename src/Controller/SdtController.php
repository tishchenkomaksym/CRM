<?php

namespace App\Controller;

use App\Calendar\CalendarEventItemCollection;
use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Calendar\HolidayCalendarEventItemBuilder;
use App\Calendar\Sdt\Tom\TomSdtLinkGenerator;
use App\Calendar\Sdt\Tom\TomSdtTitleGenerator;
use App\Calendar\Sdt\UserSdtLinkGenerator;
use App\Calendar\Sdt\UserSdtTitleGenerator;
use App\Calendar\SdtCalendarEventItemBuilder;
use App\Constants\Services;
use App\Data\Sdt\Mail\Adapter\DeleteSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\EditSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\NewSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Data\Sdt\Mail\DeleteSdtMailData;
use App\Entity\Sdt;
use App\Entity\SdtArchive;
use App\Form\SdtType;
use App\Service\HolidayService;
use App\Service\Sdt\Calendar\Tom\TomSdtCollectionBuilder;
use App\Service\Sdt\Create\BaseCreateContext;
use App\Service\Sdt\Create\BaseCreateStrategy;
use App\Service\Sdt\Create\FormValidators\SdtCount;
use App\Service\Sdt\Create\NewSDTMessageBuilder;
use App\Service\Sdt\Exception\EmailServerNotWorking;
use App\Service\Sdt\Update\BaseUpdateContext;
use App\Service\Sdt\Update\BaseUpdateStrategy;
use App\Service\Sdt\Update\UpdateSDTMessageBuilder;
use App\Service\SdtArchive\SdtArchiveBuilderFromSdt;
use App\Service\User\Sdt\LeftSdtCalculator;
use App\Service\UserInformationService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;

/**
 * @Route("/sdt")
 */
class SdtController extends AbstractController
{
    public const SDT_INDEX_ROUTE = 'sdt_index';
    /**
     * @var Twig_Environment
     */
    private $environment;

    public function __construct(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @Route("/", name="sdt_index", methods={"GET"})
     * @param HolidayService $holidayService
     * @param UserInformationService $userInformationService
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param UserSdtLinkGenerator $userSdtLinkGenerator
     * @param UserSdtTitleGenerator $titleGenerator
     * @return Response
     */
    public function index(
        HolidayService $holidayService,
        UserInformationService $userInformationService,
        LeftSdtCalculator $leftSdtCalculator,
        UserSdtLinkGenerator $userSdtLinkGenerator,
        UserSdtTitleGenerator $titleGenerator
    ): Response
    {
        $sdtCollection = $userInformationService
            ->getAllUserSdt($this->getUser());

        $calendarEventItemCollection = new CalendarEventItemCollection();
        foreach ($sdtCollection->getItems() as $sdt) {
            $calendarEventItemCollection->add(
                (new SdtCalendarEventItemBuilder(
                    $holidayService,
                    $userSdtLinkGenerator,
                    $titleGenerator
                ))->build($sdt, $this->getUser())
            );
        }

        foreach ($holidayService->getHolidays() as $holiday) {
            $calendarEventItemCollection->add(
                (new HolidayCalendarEventItemBuilder(
                    $holiday,
                    $this->container->get(Services::ROUTER)
                ))->build()
            );
        }

        return $this->render(
            'sdt/index.html.twig',
            [
                'sdts' => $sdtCollection->getItems(),
                'calendarEvents' => $calendarEventItemCollection->toJson(),
                'leftSdt' => $leftSdtCalculator->calculate($this->getUser())
            ]
        );
    }

    /**
     * @Route("/view-all", name="sdt_view_all", methods={"GET"})
     * @param HolidayService $holidayService
     * @param TomSdtCollectionBuilder $collectionBuilder
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param TomSdtLinkGenerator $linkGenerator
     * @param TomSdtTitleGenerator $titleGenerator
     * @return Response
     * @Security("is_granted('ROLE_TOM')")
     */
    public function viewAll(HolidayService $holidayService,
                            TomSdtCollectionBuilder $collectionBuilder,
                            LeftSdtCalculator $leftSdtCalculator,
                            TomSdtLinkGenerator $linkGenerator,
                            TomSdtTitleGenerator $titleGenerator

    ): Response
    {
        $sdtCollection = $collectionBuilder->buildCollection();

        $calendarEventItemCollection = new CalendarEventItemCollection();
        foreach ($sdtCollection->getItems() as $sdt) {
            $calendarEventItemCollection->add(
                (new SdtCalendarEventItemBuilder(
                    $holidayService,
                    $linkGenerator,
                    $titleGenerator
                ))->build($sdt, $this->getUser())
            );
        }

        foreach ($holidayService->getHolidays() as $holiday) {
            $calendarEventItemCollection->add(
                (new HolidayCalendarEventItemBuilder(
                    $holiday,
                    $this->container->get(Services::ROUTER)
                ))->build()
            );
        }

        return $this->render(
            'sdt/index.html.twig',
            [
                'sdts' => $sdtCollection->getItems(),
                'calendarEvents' => $calendarEventItemCollection->toJson(),
                'leftSdt' => $leftSdtCalculator->calculate($this->getUser())
            ]
        );
    }

    /**
     * @Route("/new", name="sdt_new", methods={"GET","POST"})
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param NewSdtMailFromSdtAdapter $newSdtMailFromSdtAdapter
     * @return Response
     * @throws EmailServerNotWorking
     * @throws NoDateException
     */
    public function new(Request $request,
                        Swift_Mailer $mailer,
                        HolidayService $holidayService,
                        LeftSdtCalculator $leftSdtCalculator,
                        NewSdtMailFromSdtAdapter $newSdtMailFromSdtAdapter
    ): Response
    {
        $sdt = new Sdt();
        $sdt->setUser($this->getUser());
        $form = $this->createForm(
            SdtType::class,
            $sdt,
            ['constraints' => [new SdtCount($leftSdtCalculator)]]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBuilder = new NewSDTMessageBuilder(
                $newSdtMailFromSdtAdapter->getNewSdtMail($sdt, $holidayService), $this->environment
            );
            $strategy = new BaseCreateStrategy(
                $mailer,
                $sdt,
                $messageBuilder,
                $this->getDoctrine()->getManager()
            );
            $context = new BaseCreateContext($strategy);
            $context->createSdt();
            return $this->redirectToRoute(self::SDT_INDEX_ROUTE);
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
     * @param HolidayService $holidayService
     * @return Response
     */
    public function show(Sdt $sdt, HolidayService $holidayService): Response
    {
        return $this->render(
            'sdt/show.html.twig',
            [
                'endDate' => DateCalculatorWithWeekends::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount(), $holidayService),
                'sdt' => $sdt,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="sdt_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Sdt $sdt
     * @param Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param EditSdtMailFromSdtAdapter $editSdtMailFromSdtAdapter
     * @return Response
     * @throws EmailServerNotWorking
     * @throws NoDateException
     */
    public function edit(Request $request,
                         Sdt $sdt,
                         Swift_Mailer $mailer,
                         HolidayService $holidayService,
                         LeftSdtCalculator $leftSdtCalculator,
                         EditSdtMailFromSdtAdapter $editSdtMailFromSdtAdapter
    ): Response
    {
        $form = $this->createForm(SdtType::class, $sdt,
            ['constraints' => [new SdtCount($leftSdtCalculator)]]
        );
        $oldEntity = clone $sdt;
        $oldFromDate = $sdt->getCreateDate();
        $oldCount = $sdt->getCount();
        $form->handleRequest($request);

        if ($oldFromDate !== null && $form->isSubmitted() && $form->isValid()) {
            $messageBuilder = new UpdateSDTMessageBuilder(
                $editSdtMailFromSdtAdapter->getEditSdtMail($sdt, $oldFromDate, $oldCount, $holidayService),
                $this->environment
            );
            $strategy = new BaseUpdateStrategy(
                $mailer,
                $sdt,
                $oldEntity,
                $messageBuilder,
                $this->getDoctrine()->getManager()
            );
            $context = new BaseUpdateContext($strategy);

            $sdt = $context->updateSDT();

            return $this->redirectToRoute(
                self::SDT_INDEX_ROUTE,
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
     * @param Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @param DeleteSdtMailFromSdtAdapter $deleteSdtMailFromSdtAdapter
     * @return Response
     * @throws EmailServerNotWorking
     * @throws NoDateException
     * @throws Exception
     */
    public function delete(Request $request, Sdt $sdt, Swift_Mailer $mailer, HolidayService $holidayService, DeleteSdtMailFromSdtAdapter $deleteSdtMailFromSdtAdapter): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sdt->getId(), $request->request->get('_token'))) {
            if ($this->sendDeleteSdtEmail(
                    $mailer,
                    $deleteSdtMailFromSdtAdapter->getNewSdtMail($sdt, $holidayService)
                ) === 0) {
                throw new EmailServerNotWorking(EmailServerNotWorking::MESSAGE);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $builder = new SdtArchiveBuilderFromSdt();
            $archive = new SdtArchive();
            $archive = $builder->build($sdt, $archive);
            $entityManager->persist($archive);
            $entityManager->remove($sdt);
            $entityManager->flush();
        }

        return $this->redirectToRoute(self::SDT_INDEX_ROUTE);
    }

    private function sendDeleteSdtEmail(Swift_Mailer $mailer, DeleteSdtMailData $mailData): int
    {
        $message = (new Swift_Message($mailData->getSubject()))
            ->setFrom($mailData->getFromEmail())
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
