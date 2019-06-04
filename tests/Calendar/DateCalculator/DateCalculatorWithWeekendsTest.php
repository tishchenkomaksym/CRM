<?php

namespace App\Calendar\DateCalculator;

use App\Service\HolidayService;
use DateTime;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DateCalculatorWithWeekendsTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestGetDateWithOffset
     * @param int $offset
     * @param array $holidays
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @throws Exception
     */
    public function testGetDateWithOffset(int $offset, array $holidays, DateTime $startDate, DateTime $endDate)
    {
        /** @var  HolidayService|MockObject $holidayService */
        $holidayService = $this->createMock(HolidayService::class);
        $holidayService->method('getHolidayBetweenDate')->willReturn($holidays);
        $result = DateCalculatorWithWeekends::getDateWithOffset($startDate, $offset, $holidayService);
        $this->assertEquals($endDate, $result);
    }
    public function dataProviderTestGetDateWithOffset()
    {
        return [
            [
                2,
                ['test'],
                new DateTime('2019-06-01'),
                new DateTime('2019-06-05'),
            ],
            [
                10,
                [],
                new DateTime('2019-06-01'),
                new DateTime('2019-06-14'),
            ],
            [
                24,
                ['test', 'test'],
                new DateTime('2019-06-01'),
                new DateTime('2019-07-08'),
            ],
            [
                34,
                [],
                new DateTime('2019-06-01'),
                new DateTime('2019-07-18'),
            ],
        ];
    }
}
