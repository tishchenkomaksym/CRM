<?php


namespace App\Calendar\DateCalculator;

use DateTimeInterface;

class BaseDateCalculator
{
    public static function getDateWithOffset(DateTimeInterface $startDate, int $offset)
    {
        $calculatedSdtCount = $offset > 0 ? $offset : $offset * -1;
        --$calculatedSdtCount;
        if ($calculatedSdtCount === 0) {
            return $startDate;
        }
        /** @noinspection PhpParamsInspection */

        return date_modify(clone $startDate, '+' . $calculatedSdtCount . ' days');
    }
}