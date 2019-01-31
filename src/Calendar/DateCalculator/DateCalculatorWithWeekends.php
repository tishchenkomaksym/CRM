<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 11:44
 */

namespace App\Calendar\DateCalculator;

use App\Service\HolidayService;

class DateCalculatorWithWeekends
{
    public static function getDateWithOffset(\DateTimeInterface $startDate, int $offset, HolidayService $holidayService)
    {
        $calculatedSdtCount = $offset > 0 ? $offset : $offset * -1;

        /** @noinspection PhpParamsInspection */
        $endDate = date_modify(clone $startDate, '+' . $calculatedSdtCount . ' weekdays');
        $holidaysCount = \count($holidayService->getHolidayBetweenDate($startDate, $endDate));
        if ($holidaysCount > 0) {
            date_modify($endDate, '+' . $holidaysCount . ' weekdays');
        }
        return $endDate;
    }
}
