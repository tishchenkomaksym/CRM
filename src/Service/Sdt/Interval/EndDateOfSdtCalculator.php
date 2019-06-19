<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 2:13 PM
 */

namespace App\Service\Sdt\Interval;


use App\Entity\Sdt;
use App\Service\HolidayService;
use DateTime;
use Exception;

class EndDateOfSdtCalculator
{
    /**
     * @var HolidayService
     */
    private $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     * @param Sdt $sdt
     * @return DateTime
     * @throws Exception
     */
    public function calculate(Sdt $sdt): ?DateTime
    {
        /** @var dateTime $dateTime */
        $dateTime = $sdt->getCreateDate();
        $count = $sdt->getCount() - 1;
        //Cause we calculate the same date too
        if ($count > 0) {
            $endDate = new DateTime(date('Y-m-j', strtotime("+{$count} weekdays", $dateTime->getTimestamp())));
            $holidays = count($this->holidayService->getHolidayBetweenDateNumbers($dateTime, $endDate));
            return date_modify($endDate, '+' .$holidays. ' days');
        }
        return $dateTime;
    }
}