<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 18.03.2019
 * Time: 14:55
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime\HoursRequired;

use App\Entity\PhpDeveloperLevelHoursRequired;
use App\Entity\User;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException;

class RequiredHoursCalculator
{
    /**
     * @param User $user
     * @return PhpDeveloperLevelHoursRequired
     * @throws NoRequiredHoursException
     */
    public function calculate(User $user): PhpDeveloperLevelHoursRequired
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
