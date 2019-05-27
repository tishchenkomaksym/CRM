<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 2:47 PM
 */

namespace App\Service\SalaryReport\Builder\SDTDays;

use App\Entity\Sdt;
use App\Entity\User;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\User\Sdt\LeftSdtForPeriodCalculator;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SdtDaysCalculatorTest extends TestCase
{
    /**
     * @var SdtDaysCalculator
     */
    private $calculator;
    /** @var LeftSdtForPeriodCalculator|MockObject */
    private $leftSdtCalculator;
    /** @var  EndDateOfSdtCalculator|MockObject leftSdtCalculator */
    private $endDateOfSdtCalculator;

    protected function setUp()
    {
        /** @var  LeftSdtForPeriodCalculator|MockObject leftSdtCalculator */
        $this->leftSdtCalculator = $this->createMock(LeftSdtForPeriodCalculator::class);
        $this->leftSdtCalculator->expects($this->once())->method('calculate')->willReturn(10.5);

        /** @var  EndDateOfSdtCalculator|MockObject leftSdtCalculator */
        $this->endDateOfSdtCalculator = new EndDateOfSdtCalculator();
        $this->calculator = new SdtDaysCalculator($this->leftSdtCalculator, $this->endDateOfSdtCalculator);
    }


    /**
     * @throws \Exception
     */
    public function testCalculate()
    {
        $toTime = new DateTime('2019-03-18');

        $user = new User();
        $sdt = new Sdt();
        $createDate = new DateTime('2019-03-15');

        $sdt->setCreateDate($createDate);
        $sdt->setCount(3);
        $user->addSdt($sdt);

        $return = $this->calculator->calculate($toTime, $user);
        $this->assertEquals(11.5, $return);

    }

    /**
     * @throws \Exception
     */
    public function testCalculateFuture()
    {
        $toTime = new DateTime('2019-03-26');

        $user = new User();
        $sdt = new Sdt();
        $createDate = new DateTime('2019-03-28');

        $sdt->setCreateDate($createDate);
        $sdt->setCount(3);
        $user->addSdt($sdt);

        $return = $this->calculator->calculate($toTime, $user);
        $this->assertEquals(10.5, $return);

    }

    /**
     * @throws \Exception
     */
    public function testCalculateFitureWithOneDayIn()
    {
        $toTime = new DateTime('2019-03-26');

        $user = new User();
        $sdt = new Sdt();
        $createDate = new DateTime('2019-03-26');

        $sdt->setCreateDate($createDate);
        $sdt->setCount(3);
        $user->addSdt($sdt);

        $return = $this->calculator->calculate($toTime, $user);
        $this->assertEquals(12.5, $return);

    }


    /**
     * @throws \Exception
     */
    public function testCalculateSdtWitoutLine()
    {
        $toTime = new DateTime('2019-03-18');

        $user = new User();
        $sdt = new Sdt();
        $createDate = new DateTime('2019-03-01');

        $sdt->setCreateDate($createDate);
        $sdt->setCount(3);
        $user->addSdt($sdt);

        $return = $this->calculator->calculate($toTime, $user);
        $this->assertEquals(10.5, $return);

    }
}
