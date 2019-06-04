<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 11:44
 */

namespace App\Calendar\DateCalculator;


use App\Service\HolidayService;
use DateTime;


class DateCalculatorWithWeekends
{
    public static function getDateWithOffset(DateTime $startDate, int $offset, HolidayService $holidayService)
    {
        $endDate = clone $startDate;
        if ($offset > 0) {
            date_modify($endDate, '-1 day');
            date_modify($endDate, '+' . $offset . ' weekdays');
            $holidaysCount = count($holidayService->getHolidayBetweenDate($startDate, $endDate));
            if ($holidaysCount > 0) {
                date_modify($endDate, '+' . $holidaysCount . ' weekdays');
            }
        }
        return $endDate;
    }
}
