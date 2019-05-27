<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/22/2019
 * Time: 5:35 PM
 */

namespace App\Service\MonthlySdt;

use App\Repository\MonthlySdtRepository;
use DateTime;

class MonthlySdtService
{
    //TODO move to strategy
    public static function isAllowedToGenerate(DateTime $nowDate, MonthlySdtRepository $monthlySdtRepository): bool
    {
        $lastMonthlySdt = $monthlySdtRepository->findOneBy([], ['id' => 'desc']);
        if ($lastMonthlySdt) {
            $createDate = $lastMonthlySdt->getCreateDate();
            if ($createDate) {
                return $nowDate->format('Y-m-d') !== $createDate->format('Y-m-d');
            }
        }
        return true;
    }
}
