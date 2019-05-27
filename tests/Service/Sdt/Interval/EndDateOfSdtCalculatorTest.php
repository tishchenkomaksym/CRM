<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 2:36 PM
 */

namespace App\Service\Sdt\Interval;

use App\Entity\Sdt;
use PHPUnit\Framework\TestCase;

class EndDateOfSdtCalculatorTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testCalculate()
    {
        $calculator = new EndDateOfSdtCalculator();
        $sdt = new Sdt();
        $dateTime = new \DateTime();
        $dateTime->setDate(2019, 03, 15);
        $sdt->setCreateDate($dateTime);
        $sdt->setCount(2);
        $result = $calculator->calculate($sdt);
        $this->assertEquals(18, (int)$result->format('d'));
    }


    /**
     * @throws \Exception
     */
    public function testCalculateOneDay()
    {
        $calculator = new EndDateOfSdtCalculator();
        $sdt = new Sdt();
        $dateTime = new \DateTime();
        $dateTime->setDate(2019, 03, 15);
        $sdt->setCreateDate($dateTime);
        $sdt->setCount(1);
        $result = $calculator->calculate($sdt);
        $this->assertEquals(15, (int)$result->format('d'));
    }
}
