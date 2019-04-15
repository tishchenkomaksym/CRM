<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 14:45
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime;

use App\Entity\User;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\HoursRequired\RequiredHoursCalculator;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\SpendEffectiveTime\BaseEffectiveTimeCalculator;

class BaseEffectiveTimeBuilder
{
    /**
     * @var BaseEffectiveTimeCalculator
     */
    private $baseEffectiveTimeCalculator;
    /**
     * @var RequiredHoursCalculator
     */
    private $requiredHoursCalculator;

    public function __construct(
        BaseEffectiveTimeCalculator $baseEffectiveTimeCalculator,
        RequiredHoursCalculator $requiredHoursCalculator
    )
    {
        $this->baseEffectiveTimeCalculator = $baseEffectiveTimeCalculator;
        $this->requiredHoursCalculator = $requiredHoursCalculator;
    }

    /**
     * @param User $user
     * @return BaseEffectiveTime
     * @throws NoRequiredHoursException
     * @throws \Exception
     */
    public function build(
        User $user
    ): BaseEffectiveTime {
        $time = new BaseEffectiveTime();
        $requiredHoursObject =$this->requiredHoursCalculator->calculate($user);
        $time->setRequiredTime($requiredHoursObject->getEffectiveTime());
        $spendTime = $this->baseEffectiveTimeCalculator->calculate($user);
        $time->setSpendEffectiveTime($spendTime);
        if ($time->getSpendEffectiveTime() >= $time->getRequiredTime()) {
            $time->setPassed(true);
        } else {
            $time->setPassed(false);
        }
        return $time;
    }
}
