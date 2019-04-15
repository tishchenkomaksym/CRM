<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/27/2019
 * Time: 5:11 PM
 */

namespace App\Service\User\Sdt;


use App\Entity\User;

class UsedSdtDaysCalculator
{
    /**
     * @var SdtRequestDaysCalculator
     */
    private $sdtRequestDaysCalculator;

    public function __construct(SdtRequestDaysCalculator $sdtRequestDaysCalculator)
    {
        $this->sdtRequestDaysCalculator = $sdtRequestDaysCalculator;
    }

    public function calculate(\DateTimeImmutable $startPeriod, \DateTime $endPeriod, User $user): int
    {
        $sdtArray = $user->getSdt();
        $calculateArray = [];
        foreach ($sdtArray as $sdt) {
            $createDate = $sdt->getCreateDate();
            if ($createDate >= $startPeriod && $createDate <= $endPeriod) {
                $calculateArray[] = $sdt;
            }
        }
        return $this->sdtRequestDaysCalculator->calculate($calculateArray);
    }
}