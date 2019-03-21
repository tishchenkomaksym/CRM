<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 11:42 AM
 */

namespace App\Service\User\Sdt;


use App\Entity\User;

class LeftSdtCalculator
{

    /**
     * @var SdtRequestDaysCalculator
     */
    private $daysCalculator;

    public function __construct(SdtRequestDaysCalculator $daysCalculator)
    {
        $this->daysCalculator = $daysCalculator;
    }

    public function calculate(User$user) : float
    {
        $existSDT = 0;
        foreach ($user->getMonthlySdts() as $monthlySdt) {
            $existSDT += $monthlySdt->getCount();
        }
        return $existSDT - $this->daysCalculator->calculate($user->getSdt()->toArray());
    }
}