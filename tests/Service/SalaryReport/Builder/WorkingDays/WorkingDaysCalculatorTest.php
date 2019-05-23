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
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WorkingDaysCalculatorTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testCalculate()
    {
        /** @var  HolidayService|MockObject $holidayService */
        $holidayService = $this->createMock(HolidayService::class);
        $holidayService->method('getHolidayBetweenDate')->willReturn([]);
        $baseWorkingDayCalculator = new BaseWorkingDaysCalculator($holidayService);
        $workingDaysCalculator = new CalendarWorkingDaysCalculator($baseWorkingDayCalculator);
        $salaryReportInfo = new SalaryReportInfo();
        $date = new DateTimeImmutable('2019-03-03');
        $salaryReportInfo->setCreateDate($date);
        $result = $workingDaysCalculator->calculate($salaryReportInfo);
        $this->assertEquals(21, $result);
    }
}
