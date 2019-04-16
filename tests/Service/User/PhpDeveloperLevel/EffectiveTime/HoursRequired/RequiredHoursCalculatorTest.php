<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 18.03.2019
 * Time: 15:21
 */

namespace Service\User\PhpDeveloperLevel\EffectiveTime\HoursRequired;

use App\Entity\PhpDeveloperLevel;
use App\Entity\PhpDeveloperLevelHoursRequired;
use App\Entity\User;
use App\Entity\UserPhpDeveloperLevelRelation;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\HoursRequired\RequiredHoursCalculator;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException;
use PHPUnit\Framework\TestCase;

class RequiredHoursCalculatorTest extends TestCase
{

    /**
     * @throws \App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException
     */
    public function testCalculate()
    {
        $hoursCalculator = new RequiredHoursCalculator();
        $user = new User();
        $userRelation = new UserPhpDeveloperLevelRelation();
        $phpDeveloperLevel = new PhpDeveloperLevel();
        $phpDeveloperLevelNext = new PhpDeveloperLevel();
        $phpDeveloperLevelHoursRequired = new PhpDeveloperLevelHoursRequired();
        $phpDeveloperLevelHoursRequired->setEffectiveProjectTime(1);
        $user->setPhpDeveloperLevelRelation($userRelation);
        $userRelation->setPhpDeveloperLevel($phpDeveloperLevel);
        $phpDeveloperLevel->setNextLevel($phpDeveloperLevelNext);
        $phpDeveloperLevelNext->setPhpDeveloperLevelHoursRequired($phpDeveloperLevelHoursRequired);
        $result = $hoursCalculator->calculate($user);
        $this->assertEquals($phpDeveloperLevelHoursRequired, $result);
    }

    /**
     * @throws NoRequiredHoursException
     */
    public function testCalculateException()
    {
        $hoursCalculator = new RequiredHoursCalculator();
        $user = new User();
        /** @noinspection PhpParamsInspection */
        $this->expectException(NoRequiredHoursException::class);
        $hoursCalculator->calculate($user);
    }
}

