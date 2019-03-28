<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 11:42 AM
 */

namespace App\Service\User\Sdt;


use App\Entity\User;

class LeftSdtForPeriodCalculator
{

    /**
     * @var SdtRequestDaysCalculator
     */
    private $daysCalculator;

    public function __construct(SdtRequestDaysCalculator $daysCalculator)
    {
        $this->daysCalculator = $daysCalculator;
    }

    public function calculate(User $user, \DateTime $toPeriod): float
    {
        $existSDT = 0;
        foreach ($user->getMonthlySdts() as $monthlySdt) {
            if ($monthlySdt->getCreateDate() < $toPeriod) {
                $existSDT += $monthlySdt->getCount();
            }
        }
        $sdtArray = [];
        foreach ($user->getSdt() as $sdt) {
            if ($sdt->getCreateDate() <= $toPeriod) {
                $sdtArray[] = $sdt;
            }
        }
        return $existSDT - $this->daysCalculator->calculate($sdtArray);
    }
}