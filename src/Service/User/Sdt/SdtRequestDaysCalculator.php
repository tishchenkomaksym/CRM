<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 11:43 AM
 */

namespace App\Service\User\Sdt;


use App\Entity\Sdt;

class SdtRequestDaysCalculator
{
    /**
     * @param Sdt[] $sdtArray
     * @return int|null
     */
    public function calculate(array $sdtArray): ?int
    {
        $days = 0;
        foreach ($sdtArray as $item) {
            if(!$item->getAtOwnExpense())
            {
                $days += $item->getCount();
            }
        }
        return $days;
    }
}