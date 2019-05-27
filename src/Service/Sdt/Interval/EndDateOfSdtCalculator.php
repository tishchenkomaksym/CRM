<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 2:13 PM
 */

namespace App\Service\Sdt\Interval;


use App\Entity\Sdt;
use DateTime;
use Exception;

class EndDateOfSdtCalculator
{
    /**
     * @param Sdt $sdt
     * @return DateTime
     * @throws Exception
     */
    public function calculate(Sdt $sdt): DateTime
    {
        /** @var dateTime $dateTime */
        $dateTime = $sdt->getCreateDate();
        $count = $sdt->getCount()-1;
        //Cause we calculate the same date too
        if ($count > 0) {
            return new DateTime(date('Y-m-j', strtotime("+{$count} weekdays", $dateTime->getTimestamp())));
        }
        return $dateTime;
    }
}