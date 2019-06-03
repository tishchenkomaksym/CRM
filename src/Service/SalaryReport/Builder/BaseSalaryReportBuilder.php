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
use App\Service\SalaryReport\Bonuses\Project\ElasticProjectDTOBuilder;
use App\Service\SalaryReport\Bonuses\User\UserBonusInformationBuilder;
use App\Service\SalaryReport\Builder\SDTDays\SdtDaysCalculator;
use App\Service\SalaryReport\Builder\WorkingDays\CalendarWorkingDaysCalculator;
use App\Service\SalaryReport\SalaryReportDTO;
use App\Service\User\PhpDeveloper\Hours\BaseWorkHoursInformationBuilder;
use App\Service\User\PhpDeveloper\Hours\WorkHoursInformation;
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
    /**
     * @var ElasticProjectDTOBuilder
     */
    private $projectDTOBuilder;
    /**
     * @var UserBonusInformationBuilder
     */
    private $bonusInformationBuilder;

    public function __construct(
        CalendarWorkingDaysCalculator $workingDaysCalculator,
        SdtDaysCalculator $sdtDaysCalculator,
        UsedSdtDaysCalculator $usedSdtDaysCalculator,
        BaseWorkingDaysCalculator $baseWorkingDaysCalculator,
        BaseWorkHoursInformationBuilder $baseWorkHoursInformationBuilder,
        ElasticProjectDTOBuilder $projectDTOBuilder,
        UserBonusInformationBuilder $bonusInformationBuilder
    ) {
        $this->workingDaysCalculator = $workingDaysCalculator;
        $this->sdtDaysCalculator = $sdtDaysCalculator;
        $this->usedSdtDaysCalculator = $usedSdtDaysCalculator;
        $this->baseWorkHoursInformationBuilder = $baseWorkHoursInformationBuilder;
        $this->baseWorkingDaysCalculator = $baseWorkingDaysCalculator;
        $this->projectDTOBuilder = $projectDTOBuilder;
        $this->bonusInformationBuilder = $bonusInformationBuilder;
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
        $dateForSdt = $this->getDateForSDT($newReport);
        $dateWorkingHours = $this->getDateWorkingHours($newReport);
        /** @noinspection NullPointerExceptionInspection */
        $previousDateTime = new DateTime("@{$previousReportInfo->getCreateDate()->getTimeStamp()}");

        $this->getUserBonusInformation($user, $previousDateTime, $dateForSdt);

        $returnObject->sdtCountUsed = $this->getSdtCountUsed($previousDateTime, $dateForSdt, $user);
        $returnObject->sdtCountAtOwnExpenseUsed = $this->getSdtAtOwnExpenseUsedCount($previousDateTime, $dateForSdt,
            $user);
        $returnObject->calendarWorkingDays = $this->workingDaysCalculator->calculate($newReport,
                $user->getCreateDate()) - $returnObject->sdtCountUsed - $returnObject->sdtCountAtOwnExpenseUsed;

        $returnObject->reportWorkingDays = $this->getReportWorkingDays($previousDateTime, $user->getCreateDate(),
            $dateWorkingHours,
            $returnObject->sdtCountUsed + $returnObject->sdtCountAtOwnExpenseUsed);
        $returnObject->sdtCount = $this->sdtDaysCalculator->calculate($dateForSdt, $user) - $returnObject->sdtCountAtOwnExpenseUsed;

        $returnObject->setTimeInfo($this->getTimeInfo($previousDateTime, $dateWorkingHours, $user));

        $returnObject->timeUnlogged = number_format($returnObject->getTimeInfo()->getLoggedTime() -
            $returnObject->getTimeInfo()->getRequiredTime(), 2);

        $returnObject->user = $user;
        if($userTeam = $user->getTeam()) {
            $returnObject->team = $userTeam;
        }
        return $returnObject;
    }

    /**
     * @param SalaryReportInfo $newReport
     * @return DateTime
     * @throws Exception
     */
    private function getDateForSDT(
        SalaryReportInfo $newReport
    ): DateTime {
        $dateForSdt = new DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateForSdt->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $dateForSdt->setDate($dateForSdt->format('Y'), $dateForSdt->format('m'), (int)$dateForSdt->format('d') - 1);
        $dateForSdt->setTime(23, 59, 59);
        return $dateForSdt;
    }

    /**
     * @param SalaryReportInfo $newReport
     * @return DateTime
     * @throws Exception
     */
    private function getDateWorkingHours(
        SalaryReportInfo $newReport
    ): DateTime {
        /**
         * cause calculate working day to date
         */
        $dateWorkingHours = new DateTime();
        /** @noinspection NullPointerExceptionInspection */
        $dateWorkingHours->setTimestamp($newReport->getCreateDate()->getTimestamp());
        $dateWorkingHours->setDate($dateWorkingHours->format('Y'), $dateWorkingHours->format('m'),
            (int)$dateWorkingHours->format('d'));
        return $dateWorkingHours;
    }

    /**
     * @param $previousDateTime
     * @param $dateWorkingHours
     * @param $user
     * @return WorkHoursInformation
     * @throws Exception
     */
    private function getTimeInfo(
        $previousDateTime,
        $dateWorkingHours,
        $user
    ): WorkHoursInformation {
        return $this->baseWorkHoursInformationBuilder->build(
            $previousDateTime,
            $dateWorkingHours,
            $user
        );
    }


    /**
     * @param User $user
     * @param DateTime $from
     * @param DateTime $to
     * @throws Exception
     */
    private function getUserBonusInformation(User $user, DateTime $from, DateTime $to)
    {
        $projects = $this->projectDTOBuilder->buildArray();
        $filteredProjects = [];
        foreach ($projects as $project) {
            $endDate = $project->getEndDate();
            if (($from < $endDate && $endDate < $to) || $project->getStatus() !== 'Closed') {
                $filteredProjects[] = $project;
            }
        }
        $this->bonusInformationBuilder->buildArray($user, $filteredProjects);
    }

    private function getReportWorkingDays(
        DateTime $previousDateTime,
        DateTime $startDate,
        DateTime $dateForSdt,
        int $usedSdt
    ) {
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