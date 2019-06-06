<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace App\Service\User\Sdt;

use App\Entity\Sdt;
use App\Service\HolidayService;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UsedSdtDaysCalculatorTest extends TestCase
{

    /**
     * @dataProvider calculateDateProvider
     * @param $start
     * @param $end
     * @param $count
     * @param $createDate
     * @param $result
     * @throws Exception
     */
    public function testCalculate($start, $end, $count, $createDate, $result): void
    {
        /** @var HolidayService|MockObject $mock */
        $mock = $this->createMock(HolidayService::class);
        $mock->method('getHolidayBetweenDate')->willReturn([]);
        $calculator = new UsedSdtDaysCalculator(
            new EndDateOfSdtCalculator($mock),
            new BaseWorkingDaysCalculator($mock));
        $sdt = (new Sdt())->setCount($count)->setCreateDate(new DateTime($createDate));
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $this->assertEquals($result, $calculator->calculate($startDate, $endDate, [$sdt]));
    }

    /**
     * @return array
     */
    public function calculateDateProvider(): array
    {
        return [
            ['2019-05-01', '2019-05-20', 1, '2019-05-08', 1],
            ['2019-05-30', '2019-06-30', 4, '2019-06-01', 3],
            ['2019-05-30', '2019-06-30', 4, '2019-05-28', 2]
        ];
    }
}
