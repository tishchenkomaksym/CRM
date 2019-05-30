<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/21/2019
 * Time: 6:03 PM
 */

namespace App\Service\SalaryReport\Builder\WorkingDays;

use App\Entity\SalaryReportInfo;
use App\Service\HolidayService;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WorkingDaysCalculatorTest extends TestCase
{

    /**
     * @dataProvider dataProviderTestCalculate
     * @param DateTime $startDate
     * @param int $days
     * @throws Exception
     */
    public function testCalculate(int $days, DateTime $startDate) : void
    {
        /** @var  HolidayService|MockObject $holidayService */
        $holidayService = $this->createMock(HolidayService::class);
        $holidayService->method('getHolidayBetweenDate')->willReturn([]);
        $baseWorkingDayCalculator = new BaseWorkingDaysCalculator($holidayService);
        $workingDaysCalculator = new CalendarWorkingDaysCalculator($baseWorkingDayCalculator);
        $salaryReportInfo = new SalaryReportInfo();
        $date = new DateTimeImmutable('2019-07-28');
        $salaryReportInfo->setCreateDate($date);
        $result = $workingDaysCalculator->calculate($salaryReportInfo, $startDate);
        $this->assertEquals($days, $result);
    }
    public function dataProviderTestCalculate()
    {
        return [
            [
                3,
                new DateTime('2019-07-27'),
            ],
            [
                23,
                new DateTime('2019-06-29'),
            ],
        ];
    }
}
