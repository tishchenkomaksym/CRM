<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 12:02
 */

namespace App\Data\Sdt\Mail\Adapter;

use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Data\Sdt\Mail\NewSdtMailData;
use App\Entity\Sdt;
use App\Service\HolidayService;

class NewSdtMailFromSdtAdapter
{
    /**
     * @param Sdt $sdt
     * @param HolidayService $holidayService
     * @return NewSdtMailData
     * @throws NoDateException
     */
    public static function getNewSdtMail(Sdt $sdt, HolidayService $holidayService): NewSdtMailData
    {
        $createDate = $sdt->getCreateDate();
        if ($createDate !== null) {
            $endDate = DateCalculatorWithWeekends::getDateWithOffset($createDate, $sdt->getCount(), $holidayService);
            return new NewSdtMailData(
                $createDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
                $sdt->getActing(),
                $sdt->getCount()
            );
        }
        throw new NoDateException('Entity has no create date');
    }
}