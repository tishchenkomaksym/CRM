<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace App\Service\User\Sdt;

use App\Entity\Sdt;
use App\Entity\User;
use App\Service\HolidayService;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class UsedSdtDaysCalculatorTest extends TestCase
{

    /**
     * @dataProvider calculateDateProvider
     * @param $start
     * @param $end
     * @param $count
     * @param $createDate
     * @throws Exception
     */
    public function testCalculate($start, $end, $count, $createDate, $result)
    {
        $mock = $this->createMock(HolidayService::class);
        $mock->method('getHolidayBetweenDate')->willReturn([]);

        $calculator = new UsedSdtDaysCalculator(
            new SdtRequestDaysCalculator(),
            new EndDateOfSdtCalculator(),
            new BaseWorkingDaysCalculator($mock));
        $user = new User();
        $sdt = (new Sdt())->setCount($count)->setCreateDate(new DateTime($createDate));
        $user->addSdt($sdt);
        $startDate = new DateTimeImmutable($start);
        $endDate = new DateTime($end);
        $this->assertEquals($result, $calculator->calculate($startDate, $endDate, $user));
    }

    /**
     * @return array
     */
    public function calculateDateProvider()
    {
        return [
            ['2019-05-01', '2019-05-20', 1, '2019-05-08', 1],
            ['2019-03-30', '2019-05-07', 4, '2019-05-06', 2],
            ['2019-03-30', '2019-05-07', 4, '2019-03-29', 3],
            ['2019-03-30', '2019-05-07', 4, '2019-03-11', 0],
            ['2019-03-30', '2019-05-07', 4, '2019-05-30', 0],
        ];
    }
}
