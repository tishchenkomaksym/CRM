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
use App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime\UserToProjectTimeSpendDTO;

class ProjectEffectiveTimeBuilder
{
    /**
     * @var RequiredHoursCalculator
     */
    private $requiredHoursCalculator;

    public function __construct(RequiredHoursCalculator $requiredHoursCalculator)
    {
        $this->requiredHoursCalculator = $requiredHoursCalculator;
    }

    /**
     * @param User $user
     * @param UserToProjectTimeSpendDTO[] $userProjectTimeSpendItems
     * @return BaseEffectiveTime
     * @throws NoRequiredHoursException
     */
    public function build(
        User $user,
        array $userProjectTimeSpendItems
    ): BaseEffectiveTime {
        $time = new BaseEffectiveTime();

        $requiredHoursObject = $this->requiredHoursCalculator->calculate($user);
        $time->setRequiredTime($requiredHoursObject->getEffectiveProjectTime());
        $spendTime = 0;
        foreach ($userProjectTimeSpendItems as $projectTimeSpendItem) {
            $spendTime += $projectTimeSpendItem->spendTime;
        }
        $startTime = $user->getPhpDeveloperStartTimeAndDateValue();
        if ($startTime) {
            $spendTime += $startTime->getEffectiveProjectTime();
        }
        $time->setSpendEffectiveTime($spendTime);

        if ($time->getSpendEffectiveTime() >= $time->getRequiredTime()) {
            $time->setPassed(true);
        } else {
            $time->setPassed(false);
        }
        return $time;
    }
}
