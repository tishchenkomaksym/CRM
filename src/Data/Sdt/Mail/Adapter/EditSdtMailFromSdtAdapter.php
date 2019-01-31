<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 12:02
 */

namespace App\Data\Sdt\Mail\Adapter;

use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Data\Sdt\Mail\EditSdtMailData;
use App\Entity\Sdt;
use App\Service\HolidayService;

class EditSdtMailFromSdtAdapter
{
    /**
     * @param Sdt $sdt
     * @param \DateTimeInterface $oldCreateDate
     * @param int $oldCount
     * @param HolidayService $holidayService
     * @return EditSdtMailData
     * @throws NoDateException
     */
    public static function getEditSdtMail(
        Sdt $sdt,
        \DateTimeInterface $oldCreateDate,
        int $oldCount,
        HolidayService $holidayService
    ): EditSdtMailData {
        $createDate = $sdt->getCreateDate();
        if ($createDate !== null) {
            $oldEndDate = DateCalculatorWithWeekends::getDateWithOffset(
                $oldCreateDate,
                $oldCount,
                $holidayService
            );
            $endDate = DateCalculatorWithWeekends::getDateWithOffset($createDate, $sdt->getCount(), $holidayService);

            return new EditSdtMailData(
                $oldCreateDate->format('Y-m-d'),
                $oldEndDate->format('Y-m-d'),
                $createDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
                $sdt->getActing(),
                $sdt->getCount()
            );
        }
        throw new NoDateException('Entity has no create date');
    }
}
