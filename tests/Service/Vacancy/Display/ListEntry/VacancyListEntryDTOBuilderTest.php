<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Entity\Vacancy;
use App\Service\HolidayService;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @method createMock(string $class)
 */
class VacancyListEntryDTOBuilderTest extends TestCase
{
    /**
     * @dataProvider dataProviderDate
     * @throws Exception
     */

    public function testGetExpiredTime($approveDate, $nowDate, $holidays,  $expected)
    {
        /** @var  HolidayService|MockObject $mock */
        $mock=$this->createMock(HolidayService::class);
        $mock->method('getHolidayBetweenDate')->willReturn($holidays);
        $calculator = new BaseWorkingDaysCalculator($mock);
        $expiredTimeCalculator = new ExpiredTimeCalculator($calculator);
        $dateNow = new DateTime($nowDate);
        $vacancy = new Vacancy();
        $dateTime = new DateTimeImmutable($approveDate);
        $vacancy->setAssigneeDate($dateTime);
        $this->assertEquals($expected, $expiredTimeCalculator->getExpiredTime($vacancy->getAssigneeDate(), $dateNow));
    }


    public function dataProviderDate()
    {
        return [
            ['2019-04-19 16:00:00', '2019-04-22 17:00:00',[],9.5], // end work
            ['2019-04-14 16:00:00', '2019-04-15 17:00:00',[],7.5], // end work
            ['2019-04-19 00:03:57', '2019-04-20 01:00:00',[],8.5], //work end
            ['2019-04-19 16:00:00', '2019-04-20 17:00:00',[], 2], // work end
            ['2019-04-20 16:00:00', '2019-04-21 17:00:00',[], 0], // end end
            ['2019-04-17 16:00:00', '2019-04-19 17:00:00',[], 18], // work work
            ['2019-04-17 16:00:00', '2019-04-17 17:00:00',[], 1], // same day
            ['2019-04-17 16:00:00', '2019-04-17 16:01:00',[], 0], // same day one min.
        ];
    }


}