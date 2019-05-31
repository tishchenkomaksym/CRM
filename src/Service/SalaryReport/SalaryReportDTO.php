<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/20/2019
 * Time: 5:33 PM
 */

namespace App\Service\SalaryReport;


use App\Entity\User;
use App\Service\User\PhpDeveloper\Hours\WorkHoursInformation;

class SalaryReportDTO
{
    /**
     * @var WorkHoursInformation
     */
    private $timeInfo;
    public $calendarWorkingDays;
    /**
     * @var int
     */
    public $reportWorkingDays;
    public $sdtCount;
    public $sdtCountUsed;
    public $sdtCountAtOwnExpenseUsed;
    public $salarySize;
    /** @var float */
    public $timeUnlogged;
    /** @var User */
    public $user;

    /**
     * @return WorkHoursInformation
     */
    public function getTimeInfo(): WorkHoursInformation
    {
        return $this->timeInfo;
    }

    /**
     * @param WorkHoursInformation $timeInfo
     * @return SalaryReportDTO
     */
    public function setTimeInfo(WorkHoursInformation $timeInfo): SalaryReportDTO
    {
        $this->timeInfo = $timeInfo;
        return $this;
    }
}