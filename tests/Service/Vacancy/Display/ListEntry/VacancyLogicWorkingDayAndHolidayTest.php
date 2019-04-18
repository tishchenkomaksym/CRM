<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Repository\HolidayRepository;
use App\Service\HolidayService;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VacancyLogicWorkingDayAndHolidayTest extends TestCase
{
    /**
     * @var BaseWorkingDaysCalculator|MockObject
     * @throws Exception
     */

    private $calc;

    public function setUp()
    {
        $this->calc = $this->createMock(BaseWorkingDaysCalculator::class);
    }

    /**
     * @throws NoDateException
     */
    public function testExpiredTime()
    {
        $this->calc->method('workDaysBetweenDates')->willReturn(1);
        $object = new VacancyLogicWorkingDayAndHoliday($this->calc);
        $vacancy = new Vacancy();
        $dateTime = New DateTimeImmutable('2019-11-11 01:01:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(23, $object->expiredTime($vacancy));
    }

    /**
     * @throws NoDateException
     */
    public function testExpiredTimeWithZero()
    {
        $this->calc->method('workDaysBetweenDates')->willReturn(0);
        $object = new VacancyLogicWorkingDayAndHoliday($this->calc);
        $vacancy = new Vacancy();
        $dateTime = New DateTimeImmutable('2019-11-11 01:01:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(24, $object->expiredTime($vacancy));
    }
}