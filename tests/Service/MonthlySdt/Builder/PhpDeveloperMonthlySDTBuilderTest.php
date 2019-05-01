<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/22/2019
 * Time: 4:41 PM
 */

namespace App\Service\MonthlySdt\Builder;

use App\Entity\User;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class PhpDeveloperMonthlySDTBuilderTest extends TestCase
{

    /**
     * @dataProvider dataProviderTestBuild
     * @throws Exception
     */
    public function testBuild($startDate, $endDate, $expected)
    {
        $user = new User();
        $date = new DateTime($startDate);
        $nowDate = new DateTime($endDate);
        $user->setCreateDate($date);

        $monthlySdt = PhpDeveloperMonthlySDTBuilder::build($user, $nowDate);
        self::assertEquals($expected, $monthlySdt->getCount());
    }

    public function dataProviderTestBuild()
    {
        return [
            ['2018-01-01', '2019-01-01', 2],
            ['2018-01-01', '2018-02-01', 1.5],
            ['2018-01-01', '2020-02-01', 2],
            ['2018-05-14', '2019-05-01', 2],
            ['2018-05-15', '2019-05-01', 2],
        ];
    }
}
