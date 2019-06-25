<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/27/2019
 * Time: 5:11 PM
 */

namespace App\Service\User\Sdt;


use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use Exception;

class UsedSdtDaysCalculator
{
    /**
     * @var EndDateOfSdtCalculator
     */
    private $endDateOfSdtCalculator;
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $workingDaysCalculator;

    public function __construct(
        EndDateOfSdtCalculator $endDateOfSdtCalculator,
        BaseWorkingDaysCalculator $workingDaysCalculator
    ) {
        $this->endDateOfSdtCalculator = $endDateOfSdtCalculator;
        $this->workingDaysCalculator = $workingDaysCalculator;
    }

    /**
     * @param DateTime $startPeriod
     * @param DateTime $endPeriod
     * @param array $sdtArray
     * @return int
     * @throws Exception
     */
    public function calculate(DateTime $startPeriod, DateTime $endPeriod, array $sdtArray): int
    {
        $usedSdt = 0;
        $startPeriod->setTime(00, 00, 00);
        $endPeriod->setTime(00, 00, 00);
        foreach ($sdtArray as $sdt) {
            $startDate = $sdt->getCreateDate();
            if ($startDate === null) {
                throw new \InvalidArgumentException('no date');
            }
            $startDate = new DateTime("@{$startDate->getTimeStamp()}");

            $endDate = $this->endDateOfSdtCalculator->calculate($sdt);
            $startDate->setTime(00, 00, 00);
            $endDate->setTime(00, 00, 00);
            if ($startPeriod < $startDate && $endPeriod >= $endDate) {
                $usedSdt += $this->workingDaysCalculator->getWorkingDaysBetweenDates($startDate, $endDate);
            } elseif ($startPeriod < $startDate && $endPeriod < $endDate && $startDate < $endPeriod) {
                $usedSdt += $this->workingDaysCalculator->getWorkingDaysBetweenDates($startDate, $endPeriod);
            } elseif ($startPeriod > $startDate && $endPeriod < $endDate && $startDate < $endPeriod) {
                $usedSdt += $this->workingDaysCalculator->getWorkingDaysBetweenDates($startPeriod, $endPeriod);
            } elseif ($startPeriod > $startDate && $endPeriod > $endDate && $endDate > $startPeriod) {
                $usedSdt += $this->workingDaysCalculator->getWorkingDaysBetweenDates($startPeriod, $endDate);
            }
        }
        return $usedSdt;
    }
    /**
     * @param DateTime $startPeriod
     * @param DateTime $endPeriod
     * @param array $sdtArray
     * @return int
     * @throws Exception
     */
    public function calculateForSpecialSubTeams(DateTime $startPeriod, DateTime $endPeriod, array $sdtArray): int
    {
        $usedSdt = 0;
        $startPeriod->setTime(00, 00, 00);
        $endPeriod->setTime(00, 00, 00);
        foreach ($sdtArray as $sdt) {
            $startDate = $sdt->getCreateDate();
            if ($startDate === null) {
                throw new \InvalidArgumentException('no date');
            }
            $startDate = new DateTime("@{$startDate->getTimeStamp()}");

            $endDate = $this->endDateOfSdtCalculator->calculate($sdt);
            $startDate->setTime(00, 00, 00);
            $endDate->setTime(00, 00, 00);
            if ($startPeriod < $startDate && $endPeriod > $endDate) {
                $usedSdt += $startDate->diff(date_modify($endDate, '+1 day'))->days;
            } elseif ($startPeriod < $startDate && $endPeriod < $endDate && $startDate < $endPeriod) {
                $usedSdt += $startDate->diff($endPeriod)->days;
            } elseif ($startPeriod > $startDate && $endPeriod < $endDate && $startDate < $endPeriod) {
                $usedSdt += $startPeriod->diff($endPeriod)->days;
            } elseif ($startPeriod > $startDate && $endPeriod > $endDate && $endDate > $startPeriod) {
                $usedSdt += $startPeriod->diff($endDate)->days;
            }
        }
        return $usedSdt;
    }
}