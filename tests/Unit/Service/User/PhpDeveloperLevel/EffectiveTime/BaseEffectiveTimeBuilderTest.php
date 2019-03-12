<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 15:00
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime;

use App\Entity\PhpDeveloperLevel;
use App\Entity\PhpDeveloperLevelHoursRequired;
use App\Entity\User;
use App\Entity\UserPhpDeveloperLevelRelation;
use App\Service\ElasticSearchClient;
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
        /** @var ElasticSearchClient|MockObject $mock */
        $mock = $this->createMock(ElasticSearchClient::class);
        $mock->expects($this->once())->method('getEffectiveTimePerUser')->willReturn($spendEffectiveTime);
        $builder = new BaseEffectiveTimeBuilder($mock);
        $phpDeveloperLevelFirst = new PhpDeveloperLevel();

        $phpDeveloperLevel = new PhpDeveloperLevel();
        $phpDeveloperLevelFirst->setNextLevel($phpDeveloperLevel);
        $hoursRequired = new PhpDeveloperLevelHoursRequired();
        $hoursRequired->setEffectiveTime($requiredTime);
        $phpDeveloperLevel->setPhpDeveloperLevelHoursRequired($hoursRequired);
        $user = new User();
        $user->setEmail('qwe');
        $userLevelRelation = new UserPhpDeveloperLevelRelation();
        $user->setPhpDeveloperLevelRelation($userLevelRelation);
        $userLevelRelation->setPhpDeveloperLevel($phpDeveloperLevelFirst);
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

    /**
     * @throws NoRequiredHoursException
     */
    public function testBuildException()
    {
        /** @var ElasticSearchClient|MockObject $mock */
        $mock = $this->createMock(ElasticSearchClient::class);
        $mock->expects($this->never())->method('getEffectiveTimePerUser')->willReturn(1);
        $builder = new BaseEffectiveTimeBuilder($mock);
        $user = new User();
        $user->setEmail('qwe');
        $this->expectException(NoRequiredHoursException::class);
        $builder->build($user);
    }
}
