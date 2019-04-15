<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 12:05 PM
 */

namespace App\Service\User\Sdt;

use App\Entity\Sdt;
use PHPUnit\Framework\TestCase;

class SdtRequestDaysCalculatorTest extends TestCase
{

    public function testCalculate()
    {
        $calculator = new SdtRequestDaysCalculator();
        $sdt = new Sdt();
        $sdt->setCount(1);
        $array = [$sdt];

        $this->assertEquals(1, $calculator->calculate($array));
    }
}
