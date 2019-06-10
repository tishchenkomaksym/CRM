<?php

namespace App\Controller\Sdt;

use App\Calendar\CalendarEventItemCollection;
use App\Calendar\DateCalculator\BaseDateCalculator;
use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Calendar\HolidayCalendarEventItemBuilder;
use App\Calendar\Sdt\Tom\TomSdtLinkGenerator;
use App\Calendar\Sdt\Tom\TomSdtTitleGenerator;
use App\Calendar\Sdt\UserSdtLinkGenerator;
use App\Calendar\Sdt\UserSdtTitleGenerator;
use App\Calendar\SdtCalendarEventItemBuilder;
use App\Constants\Services;
use App\Constants\UserRoles;
use App\Data\Sdt\Mail\Adapter\DeleteSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\EditSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\NewSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Data\Sdt\Mail\DeleteSdtMailData;
use App\Entity\Sdt;
use App\Entity\SdtArchive;
use App\Form\SDT\FormValidators\UpdateDate;
use App\Form\SDT\FormValidators\UpdateSdtCount;
use App\Form\SDT\TomSdtType;
use App\Form\SDT\UserSdtType;
use App\Repository\DepartmentRepository;
use App\Repository\DepartmentTeamSdtViewRulesRepository;
use App\Repository\SalaryReportInfoRepository;
use App\Repository\UserInfoRepository;
use App\Repository\TeamRepository;
use App\Service\HolidayService;
use App\Service\Sdt\Calendar\Tom\TomSdtCollectionBuilder;
use App\Service\Sdt\Create\BaseCreateContext;
use App\Service\Sdt\Create\BaseCreateStrategy;
use App\Service\Sdt\Create\FormValidators\SdtCount;
use App\Service\Sdt\Create\FormValidators\SdtPeriod;
use App\Service\Sdt\Create\NewSDTMessageBuilder;
use App\Service\Sdt\Exception\EmailServerNotWorking;
use App\Service\Sdt\Update\BaseUpdateContext;
use App\Service\Sdt\Update\BaseUpdateStrategy;
use App\Service\Sdt\Update\UpdateSDTMessageBuilder;
use App\Service\SdtArchive\SdtArchiveBuilderFromSdt;
use App\Service\User\Sdt\LeftSdtCalculator;
use App\Service\UserInformationService;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
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
    /**
     * @var Security
     */
    private $security;

    public function __construct(Twig_Environment $environment, Security $security)
    {
        $this->environment = $environment;
        $this->security = $security;
    }

    /**
     * @Route("/", name="sdt_index", methods={"GET"})
     * @param HolidayService $holidayService
     * @param UserInformationService $userInformationService
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param UserSdtLinkGenerator $userSdtLinkGenerator
     * @param UserSdtTitleGenerator $titleGenerator
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     */
    public function index(
        HolidayService $holidayService,
        UserInformationService $userInformationService,
        LeftSdtCalculator $leftSdtCalculator,
        UserSdtLinkGenerator $userSdtLinkGenerator,
        UserSdtTitleGenerator $titleGenerator,
        UserInfoRepository $userInfoRepository
    ): Response {
        $sdtCollection = $userInformationService
            ->getAllUserSdt($this->getUser());

        $calendarEventItemCollection = new CalendarEventItemCollection();
        foreach ($sdtCollection->getItems() as $sdt) {
            $calendarEventItemCollection->add(
                (new SdtCalendarEventItemBuilder(
                    $holidayService,
                    $userSdtLinkGenerator,
                    $titleGenerator
                ))->build($sdt, $this->getUser(), $userInfoRepository)
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
     * @param DepartmentTeamSdtViewRulesRepository $departmentViewRules
     * @param TomSdtCollectionBuilder $collectionBuilder
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param TomSdtLinkGenerator $tomLinkGenerator
     * @param UserSdtLinkGenerator $userSdtLinkGenerator
     * @param TomSdtTitleGenerator $titleGenerator
     * @param UserInfoRepository $userInfoRepository
     * @param TeamRepository $teamRepository
     * @param DepartmentRepository $departmentRepository
     * @return Response
     */
    public function viewAll(
        HolidayService $holidayService,
        DepartmentTeamSdtViewRulesRepository $departmentViewRules,
        TomSdtCollectionBuilder $collectionBuilder,
        LeftSdtCalculator $leftSdtCalculator,
        TomSdtLinkGenerator $tomLinkGenerator,
        UserSdtLinkGenerator $userSdtLinkGenerator,
        TomSdtTitleGenerator $titleGenerator,
        UserInfoRepository $userInfoRepository,
        TeamRepository $teamRepository,
        DepartmentRepository $departmentRepository
    ): Response {
        $sdtCollection = $collectionBuilder->buildCollection();

        $linkGenerator = $userSdtLinkGenerator;
        if ($this->security->isGranted(UserRoles::ROLE_TOM)) {
            $linkGenerator = $tomLinkGenerator;
        }

        $department = $departmentRepository->findOneBy(['id' => $this->getUser()->getTeam()->getDepartment()]);
        if ($department !== null) {
            $department = strtolower($department->getName());
        }
        $calendarEventItemCollection = new CalendarEventItemCollection();
        foreach ($sdtCollection->getItems() as $sdt) {
            $sdtUsersTeam = $teamRepository->findOneBy(['id' => $sdt->getUser()->getTeam()]);
            if ($sdtUsersTeam !== null && $department !== null) {
                $sdtUsersTeam = strtolower($sdtUsersTeam->getName());
                if($departmentViewRules->findOneBy(array('department' => $department, 'team' => $sdtUsersTeam))) {
                    $calendarEventItemCollection->add(
                        (new SdtCalendarEventItemBuilder(
                            $holidayService,
                            $linkGenerator,
                            $titleGenerator
                        ))->build($sdt, $this->getUser(), $userInfoRepository)
                    );
                }
            }
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
     * @param SalaryReportInfoRepository $salaryReportInfoRepository
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param NewSdtMailFromSdtAdapter $newSdtMailFromSdtAdapter
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     * @throws EmailServerNotWorking
     * @throws NoDateException
     * @throws NonUniqueResultException
     */
    public function new(
        SalaryReportInfoRepository $salaryReportInfoRepository,
        Request $request,
        Swift_Mailer $mailer,
        HolidayService $holidayService,
        NewSdtMailFromSdtAdapter $newSdtMailFromSdtAdapter,
        UserInfoRepository $userInfoRepository,
        LeftSdtCalculator $leftSdtCalculator
    ): Response {
        $sdt = new Sdt();
        $sdt->setUser($this->getUser());
        $formType = null;
        if ($this->security->isGranted(UserRoles::ROLE_TOM)) {
            $formType = TomSdtType::class;
        } else {
            $formType = UserSdtType::class;
        }
        $form = $this->createForm(
            $formType,
            $sdt,
            ['constraints' => [new SdtCount($leftSdtCalculator), new SdtPeriod($sdt)]]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($todaySalaryReport = $salaryReportInfoRepository->getTodaySalaryReport()) {
                $todaySalaryReport = $todaySalaryReport->getCreateDate();
                if ($this->security->isGranted(UserRoles::ROLE_TOM) === false && $form->get('createDate')->getData() <= $todaySalaryReport) {
                    return $this->redirectToRoute(self::SDT_INDEX_ROUTE);
                }
            }
            $messageBuilder = new NewSDTMessageBuilder(
                $newSdtMailFromSdtAdapter->getNewSdtMail($sdt, $holidayService, $userInfoRepository), $this->environment
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
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     * @throws Exception
     */
    public function show(Sdt $sdt, HolidayService $holidayService, UserInfoRepository $userInfoRepository): Response
    {
        if ($sdt->getUser() !== $this->getUser()) {
            $this->denyAccessUnlessGranted(UserRoles::ROLE_TOM);
        }
        $userInfo = $userInfoRepository->findOneBy(['user' => $this->getUser()->getId()]);
        if ($userInfo !== null && $userInfo->getSubTeam() === 'Central Tech Support') {
            $endDate = BaseDateCalculator::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount());
        } else {
            $endDate = DateCalculatorWithWeekends::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount(),
                $holidayService);
        }
        return $this->render(
            'sdt/show.html.twig',
            [
                'endDate' => $endDate,
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
     * @param EditSdtMailFromSdtAdapter $editSdtMailFromSdtAdapter
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     * @throws EmailServerNotWorking
     * @throws NoDateException
     */
    public function edit(

        Request $request,
        Sdt $sdt,
        Swift_Mailer $mailer,
        HolidayService $holidayService,
        EditSdtMailFromSdtAdapter $editSdtMailFromSdtAdapter,
        UserInfoRepository $userInfoRepository
    ): Response {
        if ($sdt->getUser()->getId() !== $this->getUser()->getId()) {
            $this->denyAccessUnlessGranted(UserRoles::ROLE_TOM);
        }
        $formType = null;
        if ($sdt->getUser()->getId() !== $this->getUser()->getId()) {
            $formType = TomSdtType::class;
        } else {
            $formType = UserSdtType::class;
        }
        $oldEntity = clone $sdt;
        $form = $this->createForm($formType, $sdt,
            ['constraints' => [new UpdateDate(), new UpdateSdtCount($sdt), new SdtPeriod($sdt)]]);
        $oldFromDate = $sdt->getCreateDate();
        $oldCount = $sdt->getCount();
        $form->handleRequest($request);

        if ($oldFromDate !== null && $form->isSubmitted() && $form->isValid()) {
            $messageBuilder = new UpdateSDTMessageBuilder(
                $editSdtMailFromSdtAdapter->getEditSdtMail($sdt, $oldFromDate, $oldCount, $holidayService,
                    $userInfoRepository),
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
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     * @throws EmailServerNotWorking
     * @throws NoDateException
     * @throws Exception
     */
    public function delete(
        Request $request,
        Sdt $sdt,
        Swift_Mailer $mailer,
        HolidayService $holidayService,
        DeleteSdtMailFromSdtAdapter $deleteSdtMailFromSdtAdapter,
        UserInfoRepository $userInfoRepository
    ): Response {
        if ($sdt->getUser() !== $this->getUser()) {
            $this->denyAccessUnlessGranted(UserRoles::ROLE_TOM);
        }
        if ($this->isCsrfTokenValid('delete' . $sdt->getId(), $request->request->get('_token'))) {
            if ($this->sendDeleteSdtEmail(
                    $mailer,
                    $deleteSdtMailFromSdtAdapter->getNewSdtMail($sdt, $holidayService, $userInfoRepository)
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
