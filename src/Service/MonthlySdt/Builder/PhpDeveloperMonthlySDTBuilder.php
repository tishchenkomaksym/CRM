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
use DateTimeImmutable;
use DateTimeInterface;
use Exception;

class PhpDeveloperMonthlySDTBuilder
{
    /**
     * @param User $user
     * @param DateTime $nowDate
     * @return MonthlySdt
     * @throws Exception
     */
    public static function build(User $user, DateTime $nowDate): MonthlySdt
    {
        $monthlyStdObject = new MonthlySdt();
        $monthlyStdObject->setUserId($user);
        $monthlyStdObject->setCount(self::calculateSdtCount($user->getCreateDate(), $nowDate));
        $monthlyStdObject->setCreateDate(new DateTimeImmutable());
        return $monthlyStdObject;
    }

    private static function calculateSdtCount(DateTimeInterface $createDate, DateTime $nowDate)
    {
        $date = clone $createDate;
        if ($date instanceof DateTime) {
            $date->setDate($date->format('Y'), $date->format('m'), 1);
            $date->setTime(0, 0);
        }
        $result = $nowDate->getTimestamp() - $date->getTimestamp();
        //Year check
        if ($result >= 31536000) {
            return 2;
        }

        return 1.5;
    }

}
