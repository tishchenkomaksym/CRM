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
    public function calculateArray(array $sdtArray): ?int
    {
        $days = 0;
        foreach ($sdtArray as $item) {
            $days += $this->calculateItem($item);
        }
        return $days;
    }

    public function calculateItem(Sdt $sdt)
    {
        $count = 0;
        if (!$sdt->getAtOwnExpense()) {
            $count = $sdt->getCount();
        }
        return $count;
    }

}