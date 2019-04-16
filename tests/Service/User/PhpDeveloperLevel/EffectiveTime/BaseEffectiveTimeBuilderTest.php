<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 15:00
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime;

use App\Entity\PhpDeveloperLevelHoursRequired;
use App\Entity\User;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\HoursRequired\RequiredHoursCalculator;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\SpendEffectiveTime\BaseEffectiveTimeCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BaseEffectiveTimeBuilderTest extends TestCase
{

    /**
     * @dataProvider dataProviderBuild
     * @param $spendEffectiveTime
     * @param $requiredTime
     * @param $isPassed
     * @throws NoRequiredHoursException
     */
    public function testBuild($spendEffectiveTime, $requiredTime, $isPassed): void
    {
        /** @var BaseEffectiveTimeCalculator|MockObject $mock */
        $mock = $this->createMock(BaseEffectiveTimeCalculator::class);
        $mock->expects($this->once())->method('calculate')->willReturn($spendEffectiveTime);
        /** @var RequiredHoursCalculator|MockObject $requiredMock */
        $requiredMock = $this->createMock(RequiredHoursCalculator::class);
        $hoursRequired=new PhpDeveloperLevelHoursRequired();
        $hoursRequired->setEffectiveTime($requiredTime);
        $requiredMock->expects($this->once())->method('calculate')->willReturn($hoursRequired);
        $builder = new BaseEffectiveTimeBuilder($mock, $requiredMock);
        $user = new User();
        $result = $builder->build($user);
        $this->assertEquals($result->getRequiredTime(), $requiredTime);
        $this->assertEquals($result->getSpendEffectiveTime(), $spendEffectiveTime);
        $this->assertEquals($result->isPassed(), $isPassed);
    }

    public function dataProviderBuild(): array
    {
        return [
            [2, 2, true],
            [1, 2, false],
            [3, 2, true],
        ];
    }


}
