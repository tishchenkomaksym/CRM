<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/27/2019
 * Time: 5:11 PM
 */

namespace App\Service\User\Sdt;


use App\Entity\User;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use DateTimeImmutable;
use Exception;

class UsedSdtDaysCalculator
{
    /**
     * @var SdtRequestDaysCalculator
     */
    private $sdtRequestDaysCalculator;
    /**
     * @var EndDateOfSdtCalculator
     */
    private $endDateOfSdtCalculator;
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $workingDaysCalculator;

    public function __construct(SdtRequestDaysCalculator $sdtRequestDaysCalculator, EndDateOfSdtCalculator $endDateOfSdtCalculator, BaseWorkingDaysCalculator $workingDaysCalculator)
    {
        $this->sdtRequestDaysCalculator = $sdtRequestDaysCalculator;
        $this->endDateOfSdtCalculator = $endDateOfSdtCalculator;
        $this->workingDaysCalculator = $workingDaysCalculator;
    }

    /**
     * @param DateTimeImmutable $startPeriod
     * @param DateTime $endPeriod
     * @param User $user
     * @return int
     * @throws Exception
     */
    public function calculate(DateTimeImmutable $startPeriod, DateTime $endPeriod, User $user): int
    {
        $sdtArray = $user->getSdt();
        $usedSdt = 0;
        foreach ($sdtArray as $sdt) {
            $startDate = $sdt->getCreateDate();
            $endDate = $this->endDateOfSdtCalculator->calculate($sdt);
            if ($startPeriod < $startDate && $endPeriod > $endDate) {
                $usedSdt += $this->sdtRequestDaysCalculator->calculateItem($sdt);
            } elseif ($startPeriod < $startDate && $endPeriod < $endDate) {
                $diffBetweenEndDate = $startDate->diff($endPeriod);
                //cause count of days
                $usedSdt += $diffBetweenEndDate->days + 1;
            } elseif ($startPeriod > $startDate && $endPeriod > $endDate && $endDate > $startPeriod) {
                $monthStartDate = new DateTime();
                /** @noinspection NullPointerExceptionInspection */
                $monthStartDate->setDate(
                    (int)$startPeriod->format('Y'),
                    $startPeriod->format('m'),
                    $startPeriod->format('d')
                );
                $monthStartDate->setTime(0, 0);
                $usedSdt += $this->workingDaysCalculator->getWorkingDaysBetweenDates($monthStartDate, $endDate);
            }
        }
        return $usedSdt;
    }
}