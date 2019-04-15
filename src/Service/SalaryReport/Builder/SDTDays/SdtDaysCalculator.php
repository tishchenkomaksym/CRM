<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/20/2019
 * Time: 10:55 PM
 */

namespace App\Service\SalaryReport\Builder\SDTDays;

use App\Entity\User;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\User\Sdt\LeftSdtForPeriodCalculator;

class SdtDaysCalculator
{
    /**
     * @var LeftSdtForPeriodCalculator
     */
    private $leftSdtCalculator;
    /**
     * @var EndDateOfSdtCalculator
     */
    private $endDateOfSdtCalculator;

    public function __construct(LeftSdtForPeriodCalculator $leftSdtCalculator, EndDateOfSdtCalculator $endDateOfSdtCalculator)
    {

        $this->leftSdtCalculator = $leftSdtCalculator;
        $this->endDateOfSdtCalculator = $endDateOfSdtCalculator;
    }

    /**
     * @param \DateTime $to
     * @param User $user
     * @return float
     * @throws \Exception
     */
    public function calculate(\DateTime $to, User $user): float
    {
        $leftSdt = $this->leftSdtCalculator->calculate($user, $to);
        $sdtArray = $user->getSdt();
        foreach ($sdtArray as $sdt) {
            $endDate = $this->endDateOfSdtCalculator->calculate($sdt);
            if ($endDate > $to && $sdt->getCreateDate() <= $to) {
                $diffBetweenEndDate = $to->diff($endDate);
                if ($diffBetweenEndDate->days > 0) {
                    $leftSdt += $diffBetweenEndDate->days;
                }
            }
        }
        return $leftSdt;
    }
}