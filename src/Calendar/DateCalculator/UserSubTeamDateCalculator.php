<?php


namespace App\Calendar\DateCalculator;

use App\Entity\Sdt;
use App\Entity\UserInfo;
use App\Service\HolidayService;
use DateTime;
use Exception;

class UserSubTeamDateCalculator
{
    /**
     * @param UserInfo $userInfo
     * @param Sdt $sdt
     * @param HolidayService $holidayService
     * @throws Exception
     * @return DateTime
     */
    public function getDateWithOffset(?UserInfo $userInfo, Sdt $sdt, HolidayService $holidayService) {
        if ($userInfo !== null && $userInfo->getSubTeam() === 'Central Tech Support') {
            $endDate = BaseDateCalculator::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount());
        } else {
            $endDate = DateCalculatorWithWeekends::getDateWithOffset($sdt->getCreateDate(), $sdt->getCount(),
                $holidayService);
        }
        return $endDate;
    }
}