<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/22/2019
 * Time: 10:26 AM
 */

namespace App\Service\MonthlySdt\Builder;

use App\Entity\MonthlySdt;
use App\Entity\User;
use DateTime;
use DateTimeInterface;

class PhpDeveloperMonthlySDTBuilder
{
    /**
     * @param User $user
     * @param DateTime $nowDate
     * @return MonthlySdt
     * @throws \Exception
     */
    public static function build(User $user, DateTime $nowDate): MonthlySdt
    {
        $monthlyStdObject = new MonthlySdt();
        $monthlyStdObject->setUserId($user);
        $monthlyStdObject->setCount(self::calculateSdtCount($user->getCreateDate(), $nowDate));
        $monthlyStdObject->setCreateDate(new \DateTimeImmutable());
        return $monthlyStdObject;
    }

    private static function calculateSdtCount(DateTimeInterface $createDate, DateTime $nowDate)
    {
        $result = $nowDate->getTimestamp() - $createDate->getTimestamp();
        //Year check
        if ($result >= 31536000) {
            return 2;
        }

        return 1.5;
    }

}
