<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 14:45
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime;

use App\Entity\User;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\SpendEffectiveTime\BaseEffectiveTimeCalculator;

class BaseEffectiveTimeBuilder
{
    /**
     * @var BaseEffectiveTimeCalculator
     */
    private $baseEffectiveTimeCalculator;

    public function __construct(BaseEffectiveTimeCalculator $baseEffectiveTimeCalculator)
    {
        $this->baseEffectiveTimeCalculator = $baseEffectiveTimeCalculator;
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
        $requiredHoursObject = $this->getPhpDeveloperHoursRequired($user);
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

    /**
     * @param User $user
     * @return \App\Entity\PhpDeveloperLevelHoursRequired
     * @throws NoRequiredHoursException
     */
    private function getPhpDeveloperHoursRequired(User $user): \App\Entity\PhpDeveloperLevelHoursRequired
    {
        $phpDeveloperRelation = $user->getPhpDeveloperLevelRelation();
        if ($phpDeveloperRelation === null || $phpDeveloperRelation->getPhpDeveloperLevel(
            ) === null || $phpDeveloperRelation->getPhpDeveloperLevel()->getNextLevel(
            ) === null || $phpDeveloperRelation->getPhpDeveloperLevel()->getNextLevel(
            )->getPhpDeveloperLevelHoursRequired() === null) {
            throw new NoRequiredHoursException(NoRequiredHoursException::MESSAGE);
        }
        return $phpDeveloperRelation->getPhpDeveloperLevel()->getNextLevel()->getPhpDeveloperLevelHoursRequired();
    }
}
