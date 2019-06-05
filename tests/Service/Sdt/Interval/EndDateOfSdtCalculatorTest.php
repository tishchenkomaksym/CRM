<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 2:36 PM
 */

namespace App\Service\Sdt\Interval;

use App\Entity\Sdt;
use App\Service\HolidayService;
use PHPUnit\Framework\TestCase;

class EndDateOfSdtCalculatorTest extends TestCase
{
    /**
     * @var HolidayService
     */
    private $holidayService;

    public function setUp()
    {

        $this->holidayService = $this->createMock(HolidayService::class);
    }
    /**
     * @throws \Exception
     */
    public function testCalculate()
    {
        $calculator = new EndDateOfSdtCalculator($this->holidayService);
        $sdt = new Sdt();
        $dateTime = new \DateTime();
        $dateTime->setDate(2019, 03, 15);
        $sdt->setCreateDate($dateTime);
        $sdt->setCount(4);
        $result = $calculator->calculate($sdt);
        $this->assertEquals(20, (int)$result->format('d'));
    }


    /**
     * @throws \Exception
     */
    public function testCalculateOneDay()
    {
        $calculator = new EndDateOfSdtCalculator($this->holidayService);
        $sdt = new Sdt();
        $dateTime = new \DateTime();
        $dateTime->setDate(2019, 06, 05);
        $sdt->setCreateDate($dateTime);
        $sdt->setCount(32);
        $result = $calculator->calculate($sdt);
        $this->assertEquals(18, (int)$result->format('d'));
    }
}
