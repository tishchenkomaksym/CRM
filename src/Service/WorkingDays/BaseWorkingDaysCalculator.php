<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 13.03.2019
 * Time: 12:56
 */

namespace App\Service\WorkingDays;

use App\Service\HolidayService;
use App\Service\User\PhpDeveloper\Hours\BaseWorkHoursInformationBuilder;
use DateTime;

class BaseWorkingDaysCalculator
{
    /**
     * @var HolidayService
     */
    private $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function getWorkingHoursBetweenDates(DateTime $from, DateTime $to): float
    {
        return $this->getWorkingDaysBetweenDates($from, $to) * BaseWorkHoursInformationBuilder::HOURS_IN_WORKING_DAY;
    }

    public function getWorkingDaysBetweenDates(DateTime $from, DateTime $to): int
    {
        $daysCount = $this->workDaysBetweenDates($from, $to);
        $holidaysCount = count($this->holidayService->getHolidayBetweenDate($from, $to));
        return ($daysCount - $holidaysCount);
    }

    public function workDaysBetweenDates(DateTime $date1, DateTime $date2)
    {
        $workdays = 0;
        $dayOfTheWeek = date('N', $date1->getTimestamp());
        if ($dayOfTheWeek <= 5) {
            $workdays++;
        }
        $differenceInDays = $date1->diff($date2)->days;
        for ($i = 1; $i <= $differenceInDays; $i++) {
            $dayOfTheWeek = date('N', strtotime("+{$i} days", $date1->getTimestamp()));
            if ($dayOfTheWeek <= 5) {
                $workdays++;
            }
        }

        return $workdays;
    }

    public function getWorkingDaysBetweenDatesNumbers(DateTime $from, DateTime $to)
    {
        $daysCount = $this->workDaysBetweenDatesNumbers($from, $to);
        $holidaysCount = count($this->holidayService->getHolidayBetweenDate($from, $to));
        return ($daysCount - $holidaysCount);
    }

    public function workDaysBetweenDatesNumbers(DateTime $date1, DateTime $date2)
    {
        $calculationDate1 = clone $date1;
        $calculationDate1->setTime(00, 00, 01);


        $calculationDate2 = clone $date2;
        $calculationDate2->setTime(00, 00, 01);


        $workdays = 0;
        $dayOfTheWeek = date('N', $calculationDate1->getTimestamp());
        if ($dayOfTheWeek <= 5) {
            $workdays++;
        }
        $differenceInDays = $calculationDate1->diff($calculationDate2)->days;
        for ($i = 1; $i <= $differenceInDays; $i++) {
            $dayOfTheWeek = date('N', strtotime("+{$i} days", $calculationDate1->setTime(00, 00, 00)->getTimestamp()));
            if ($dayOfTheWeek <= 5) {
                $workdays++;
            }
        }

        return $workdays;
    }
}
