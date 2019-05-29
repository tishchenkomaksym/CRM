<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/20/2019
 * Time: 6:52 PM
 */

namespace App\Service\SalaryReport\Builder\WorkingDays;

use App\Entity\SalaryReportInfo;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateInterval;
use DateTime;
use Exception;

class CalendarWorkingDaysCalculator
{
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $workingDaysCalculator;

    public function __construct(BaseWorkingDaysCalculator $workingDaysCalculator)
    {
        $this->workingDaysCalculator = $workingDaysCalculator;
    }

    /**
     * @param SalaryReportInfo $salaryReportInfo
     * @param DateTime $dateTime
     * @return float|int
     * @throws Exception
     */
    public function calculate(SalaryReportInfo $salaryReportInfo, DateTime $dateTime)
    {
        $date = $salaryReportInfo->getCreateDate();
        $monthStartDate = new DateTime();
        $monthStartDate->setTime(0, 0, 0);
        /** @noinspection NullPointerExceptionInspection */
        if ($date->format('m') === $dateTime->format('m') &&
            $date->format('Y') === $dateTime->format('Y')) {
            /** @noinspection NullPointerExceptionInspection */
            $monthStartDate->setDate((int)$date->format('Y'), $date->format('m'), $dateTime->format('d'));
            $toDate = clone $monthStartDate;
            $toDate->modify('last day of this month')->modify('+1 day');
        } else {
            /** @noinspection NullPointerExceptionInspection */
            $monthStartDate->setDate((int)$date->format('Y'), $date->format('m'), 01);
            $toDate = clone $monthStartDate;
            $toDate = date_modify($toDate, '+1 month');
        }
        //Cause we have get here first day of next month
        $interval = new DateInterval('P1D');
        $interval->invert = 1;
        $toDate->add($interval);
        return $this->workingDaysCalculator->getWorkingDaysBetweenDates($monthStartDate, $toDate);
    }
}