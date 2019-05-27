<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 12:05 PM
 */

namespace App\Service\User\Sdt;

use App\Entity\MonthlySdt;
use App\Entity\Sdt;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LeftSdtCalculatorTest extends TestCase
{

    public function testCalculate(): void
    {
        $requestDaysCalculator = new SdtRequestDaysCalculator();
        $calculator = new LeftSdtCalculator($requestDaysCalculator);
        $user = new User();
        $sdt = new Sdt();
        $sdt->setCount(1);
        $user->addSdt($sdt);
        $monthlySdt = new MonthlySdt();
        $monthlySdt->setCount(1.5);
        $user->addMonthlySdt($monthlySdt);
        $this->assertEquals(0.5, $calculator->calculate($user));
    }
}
