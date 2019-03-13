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
    public function getWorkingHoursBetweenDates(DateTime $from, DateTime $to)
    {
        $daysCount = $this->workDaysBetweenDates($from, $to);
        $holidaysCount = count($this->holidayService->getHolidayBetweenDate($from, $to));
        return ($daysCount - $holidaysCount) * BaseWorkHoursInformationBuilder::HOURS_IN_WORKING_DAY;
    }

    public function workDaysBetweenDates(DateTime $date1, DateTime $date2)
    {
        $workdays = 0;
        $dayOfTheWeek = date('N', $date1->getTimestamp());
        if ($dayOfTheWeek <= 5) {
            $workdays++;
        }
        $differenceInDays = $date1->diff($date2)->d;
        for ($i = 1; $i <= $differenceInDays; $i++) {
            $dayOfTheWeek = date('N', strtotime("+{$i} days", $date1->getTimestamp()));
            if ($dayOfTheWeek <= 5) {
                $workdays++;
            }
        }

        return $workdays;
    }
}
