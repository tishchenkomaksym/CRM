<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 13.03.2019
 * Time: 13:00
 */

namespace App\Service\WorkingDays;

use App\Entity\Holiday;
use App\Service\HolidayService;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Date;

class BaseWorkingDaysCalculatorTest extends TestCase
{
    /**
     * @var BaseWorkingDaysCalculator
     */
    protected $calculator;

    /**
     * @var HolidayService
     */
    private $holidayService;

    public function setUp()
    {

        $this->holidayService = $this->createMock(HolidayService::class);
        $this->holidayService->method('getHolidayBetweenDate')->willReturn(
            [new Holiday()]
        );
        $this->calculator = new BaseWorkingDaysCalculator($this->holidayService);
    }

    /**
     * @dataProvider dataProviderWorkDaysBetweenDates
     * @throws \Exception
     */
    public function testWorkDaysBetweenDates($start, $end, $aspected)
    {
        $from = new DateTime();
        $from->setDate(2019, 01, $start);
        $toDate = new DateTime();
        $toDate->setDate(2019, 01, $end);
        $result = $this->calculator->workDaysBetweenDates($from, $toDate);
        $this->assertEquals($aspected, $result);
    }

    public function dataProviderWorkDaysBetweenDates()
    {
        return [
            [5, 7, 1],
            [1, 2, 2],
            [1, 3, 3],
            [1, 1, 1],
            [1, 8, 6],
        ];
    }

    /**
     * @throws \Exception
     */
    public function testGetWorkingHoursBetweenDates()
    {
        $start = 1;
        $end = 2;
        $from = new DateTime();
        $from->setDate(2019, 01, $start);
        $toDate = new DateTime();
        $toDate->setDate(2019, 01, $end);
        $result = $this->calculator->getWorkingHoursBetweenDates($from, $toDate);
        $this->assertEquals(7.5, $result);
    }


    public function testWorkDaysBetweenDatesNumbers()
    {
        $date = new DateTime('2019-04-13');
        $date2 = new DateTime('2019-04-14');
        $result = $this->calculator->workDaysBetweenDatesNumbers($date, $date2);
        $this->assertEquals(0, $result);
    }
}
