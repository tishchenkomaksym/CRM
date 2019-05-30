<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/20/2019
 * Time: 6:51 PM
 */

namespace App\Service\SalaryReport\Builder;


use App\Entity\SalaryReportInfo;
use App\Entity\User;
use App\Service\SalaryReport\Builder\SDTDays\SdtDaysCalculator;
use App\Service\SalaryReport\Builder\WorkingDays\CalendarWorkingDaysCalculator;
use App\Service\SalaryReport\SalaryReportDTO;
use App\Service\User\PhpDeveloper\Hours\BaseWorkHoursInformationBuilder;
use App\Service\User\Sdt\Filter\AtOwnExpenseFilter;
use App\Service\User\Sdt\Filter\NotAtOwnExpenseFilter;
use App\Service\User\Sdt\UsedSdtDaysCalculator;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use Exception;

class BaseSalaryReportBuilder
{
    private $workingDaysCalculator;
    /**
     * @var SdtDaysCalculator
     */
    private $sdtDaysCalculator;
    /**
     * @var UsedSdtDaysCalculator
     */
    private $usedSdtDaysCalculator;
    /**
     * @var BaseWorkHoursInformationBuilder
     */
    private $baseWorkHoursInformationBuilder;
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $baseWorkingDaysCalculator;

    public function __construct(
        CalendarWorkingDaysCalculator $workingDaysCalculator,
        SdtDaysCalculator $sdtDaysCalculator,
        UsedSdtDaysCalculator $usedSdtDaysCalculator,
        BaseWorkingDaysCalculator $baseWorkingDaysCalculator,
        BaseWorkHoursInformationBuilder $baseWorkHoursInformationBuilder
    ) {
        $this->workingDaysCalculator = $workingDaysCalculator;
        $this->sdtDaysCalculator = $sdtDaysCalculator;
        $this->usedSdtDaysCalculator = $usedSdtDaysCalculator;
        $this->baseWorkHoursInformationBuilder = $baseWorkHoursInformationBuilder;
        $this->baseWorkingDaysCalculator = $baseWorkingDaysCalculator;
    }

    /**
     * @param SalaryReportInfo $previousReportInfo
     * @param SalaryReportInfo $newReport
     * @param User $user
     * @return SalaryReportDTO
     * @throws Exception
     */
    public function build(
        SalaryReportInfo $previousReportInfo,
        SalaryReportInfo $newReport,
        User $user
    ): SalaryReportDTO {
        $returnObject = new SalaryReportDTO();
        $dateForSdt = new DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateForSdt->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $dateForSdt->setDate($dateForSdt->format('Y'), $dateForSdt->format('m'), (int)$dateForSdt->format('d') - 1);
        $dateForSdt->setTime(23, 59, 59);

        /**
         * cause calculate working day to date
         */
        $dateWorkingHours = new DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateWorkingHours->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $dateWorkingHours->setDate($dateWorkingHours->format('Y'), $dateWorkingHours->format('m'),
            (int)$dateWorkingHours->format('d'));
        $previousDateTime = new DateTime("@{$previousReportInfo->getCreateDate()->getTimeStamp()}");

        $returnObject->sdtCountUsed = $this->getSdtCountUsed($previousDateTime, $dateForSdt, $user);
        $returnObject->sdtCountAtOwnExpenseUsed = $this->getSdtAtOwnExpenseUsedCount($previousDateTime, $dateForSdt,
            $user);
        $returnObject->calendarWorkingDays = $this->workingDaysCalculator->calculate($newReport, $user->getCreateDate()) - $returnObject->sdtCountUsed - $returnObject->sdtCountAtOwnExpenseUsed;

        $returnObject->reportWorkingDays = $this->getReportWorkingDays($previousDateTime, $user->getCreateDate(), $dateWorkingHours,
            $returnObject->sdtCountUsed + $returnObject->sdtCountAtOwnExpenseUsed);
        $returnObject->sdtCount = $this->sdtDaysCalculator->calculate($dateForSdt, $user) - $returnObject->sdtCountAtOwnExpenseUsed;
        $timeInfo = $this->baseWorkHoursInformationBuilder->build(
            $previousDateTime,
            $dateWorkingHours,
            $user
        );
        $returnObject->setTimeInfo($timeInfo);
        $returnObject->timeUnlogged = number_format($returnObject->getTimeInfo()->getLoggedTime() -
            $returnObject->getTimeInfo()->getRequiredTime(), 2);

        $returnObject->user = $user;
        return $returnObject;
    }

    private function getReportWorkingDays(DateTime $previousDateTime, DateTime $startDate,  DateTime $dateForSdt, int $usedSdt)
    {
        if ($previousDateTime < $startDate) {
            $previousDateTime = $startDate;
        }
        return $this->baseWorkingDaysCalculator->getWorkingDaysBetweenDates($previousDateTime, $dateForSdt) - $usedSdt;
    }

    /**
     * @param DateTime $previousReportDate
     * @param DateTime $nowTime
     * @param User $user
     * @return int
     * @throws Exception
     */
    private function getSdtCountUsed(
        DateTime $previousReportDate,
        DateTime $nowTime,
        User $user
    ): int {
        return $this->usedSdtDaysCalculator->calculate($previousReportDate, $nowTime,
            (new NotAtOwnExpenseFilter())->filter($user->getSdt()->toArray()));

    }

    /**
     * @param DateTime $previousReportDate
     * @param DateTime $nowTime
     * @param User $user
     * @return int
     * @throws Exception
     */
    private function getSdtAtOwnExpenseUsedCount(
        DateTime $previousReportDate,
        DateTime $nowTime,
        User $user
    ) {
        return $this->usedSdtDaysCalculator->calculate($previousReportDate,
            $nowTime, (new AtOwnExpenseFilter())->filter($user->getSdt()->toArray()));

    }
}