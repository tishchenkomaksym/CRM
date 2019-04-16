<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 18.03.2019
 * Time: 12:31
 */

namespace Service\User\PhpDeveloperLevel\EffectiveTime\SpendEffectiveTime;

use App\Entity\PhpDeveloperStartTimeAndDateValue;
use App\Entity\User;
use App\Service\ElasticSearchClient;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\SpendEffectiveTime\BaseEffectiveTimeCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BaseEffectiveTimeCalculatorTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testCalculate()
    {
        $time = 12.2;
        /** @var ElasticSearchClient|MockObject $mock */
        $mock = $this->createMock(ElasticSearchClient::class);
        $mock->expects($this->once())->method('getEffectiveTimePerUserDate')->willReturn($time);
        $calculator = new BaseEffectiveTimeCalculator($mock);
        $user = new User();
        $startTimeValues = new PhpDeveloperStartTimeAndDateValue();
        $startTimeValues->setEffectiveTime(1);
        $startTimeValues->setCreateDate(new \DateTimeImmutable());
        $user->setPhpDeveloperStartTimeAndDateValue($startTimeValues);
        $result = $calculator->calculate($user);
        $this->assertEquals(13.2, $result);

    }
}
