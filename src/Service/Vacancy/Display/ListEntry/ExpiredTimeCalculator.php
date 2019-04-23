<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use DateTimeImmutable;
use Exception;

class ExpiredTimeCalculator
{
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $hoursInformation;

    public function __construct(BaseWorkingDaysCalculator $hoursInformation)
    {
        $this->hoursInformation = $hoursInformation;
    }

    /**
     * @param DateTimeImmutable $date
     * @param DateTime $dateNow
     * @return float
     * @throws Exception
     */
    public function getExpiredTime(DateTimeImmutable $date, DateTime $dateNow): float
    {

        $approve = new DateTime("@{$date->getTimeStamp()}");

        $quantityDays = $this->hoursInformation->getWorkingDaysBetweenDatesNumbers($approve, $dateNow);
        $allDaysCount = $dateNow->diff($approve)->d + 1;

        $lastSettingHour = $this->getLastSettingHour($date->format('H'));
        $lastDateHours = $this->getLastDateHour($dateNow->format('H'));


        if ($quantityDays === 0) {
            $returnValue = 0;
        } else if ($quantityDays === 1) {
            if ($allDaysCount !== $quantityDays) {
                if ($this->hoursInformation->getWorkingDaysBetweenDatesNumbers($approve, $approve) === 0) {
                    $returnValue = 8.5 - $lastDateHours;
                } else {
                    $returnValue = 8.5 - $lastSettingHour;
                }
            } else {
                $returnValue = 8.5 - $lastSettingHour - $lastDateHours;
            }
        } else {
            $returnValue = $quantityDays * 8.5 - $lastSettingHour - $lastDateHours;
        }
        return $returnValue;
    }

    private function getLastDateHour($dateHours): float
    {
        $returnValue = 0;
        if ($dateHours <= 18 && $dateHours >= 9) {
            $returnValue = 18 - $dateHours;
        }
        return $returnValue;
    }

    private function getLastSettingHour($approveHours): float
    {
        $returnValue = 0;
        if ($approveHours <= 18 && $approveHours >= 9) {
            $returnValue = $approveHours - 9.5;
        }
        return $returnValue;
    }
}