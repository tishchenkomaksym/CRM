<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 12:02
 */

namespace App\Data\Sdt\Mail\Adapter;

use App\Calendar\DateCalculator\BaseDateCalculator;
use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Data\Sdt\Mail\EditSdtMailData;
use App\Entity\Sdt;
use App\Repository\SDTEmailAssigneeRepository;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;
use DateTimeInterface;

class EditSdtMailFromSdtAdapter
{

    /**
     * @var SDTEmailAssigneeRepository
     */
    private $SDTEmailAssigneeRepository;

    public function __construct(SDTEmailAssigneeRepository $SDTEmailAssigneeRepository)
    {
        $this->SDTEmailAssigneeRepository = $SDTEmailAssigneeRepository;
    }

    public const LETTER_DATE_FORMAT = 'Y-m-d';

    /**
     * @param Sdt $sdt
     * @param DateTimeInterface $oldCreateDate
     * @param int $oldCount
     * @param HolidayService $holidayService
     * @param UserInfoRepository $userInfoRepository
     * @return EditSdtMailData
     * @throws NoDateException
     */
    public function getEditSdtMail(
        Sdt $sdt,
        DateTimeInterface $oldCreateDate,
        int $oldCount,
        HolidayService $holidayService,
        UserInfoRepository $userInfoRepository
    ): EditSdtMailData
    {
        $createDate = $sdt->getCreateDate();
        if ($createDate !== null) {
            $userInfo = $userInfoRepository->findOneBy(['user' => $sdt->getUser()->getId()]);
            if ($userInfo !== null && $userInfo->getSubTeam() === 'Central Tech Support') {
                $oldEndDate = BaseDateCalculator::getDateWithOffset($oldCreateDate, $oldCount);
                $endDate = BaseDateCalculator::getDateWithOffset($createDate, $sdt->getCount());
            } else {
                $oldEndDate = DateCalculatorWithWeekends::getDateWithOffset($oldCreateDate, $oldCount, $holidayService);
                $endDate = DateCalculatorWithWeekends::getDateWithOffset($createDate, $sdt->getCount(), $holidayService);
            }
            $emails = [];
            foreach ($this->SDTEmailAssigneeRepository->findBy(['user' => $sdt->getUser()->getId()]) as $email) {
                $emails[] = $email->getEmail();
            }
            return new EditSdtMailData(
                $sdt->getUser()->getName(),
                $oldCreateDate->format(self::LETTER_DATE_FORMAT),
                $oldEndDate->format(self::LETTER_DATE_FORMAT),
                $createDate->format(self::LETTER_DATE_FORMAT),
                $endDate->format(self::LETTER_DATE_FORMAT),
                $sdt->getActing(),
                $sdt->getCount(),
                $sdt->getAtOwnExpense(),
                $emails
            );
        }
        throw new NoDateException('Entity has no create date');
    }
}
