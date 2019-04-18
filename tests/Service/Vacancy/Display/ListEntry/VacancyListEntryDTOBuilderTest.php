<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
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
     * @throws Exception
     * @var BaseWorkingDaysCalculator|MockObject
     */

    private $calc;

    public function setUp()
    {
        $this->calc = $this->createMock(BaseWorkingDaysCalculator::class);
    }

    /**
     * @throws NoDateException
     */
    public function testGetExpiredTime()
    {
        $this->calc->method('workDaysBetweenDates')->willReturn(1);
        $dateNow = new DateTime();
        $object = new VacancyListEntryDTOBuilder($this->calc);
        $vacancy = new Vacancy();
        $dateTime = New DateTimeImmutable('2019-11-11 01:01:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(23, $object->getExpiredTime($vacancy, $dateNow));
    }

    public function testGetExpiredTimeQwe()
    {
        $this->calc->method('workDaysBetweenDates')->willReturn(1);
        $object = new VacancyListEntryDTOBuilder($this->calc);
        $vacancy = new Vacancy();
        $dateTime = New DateTimeImmutable('2019-04-17 16:01:01');
        $dateTimeFor = New DateTime('2019-04-18 20:02:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(8, $object->getExpiredTime($vacancy, $dateTimeFor));
    }

    /**
     * @throws NoDateException
     */

    public function testGetExpiredTimeSecond()
    {
        $this->calc->method('workDaysBetweenDates')->willReturn(2);
        $object = new VacancyListEntryDTOBuilder($this->calc);
        $vacancy = new Vacancy();
        $dateNow = new DateTime();
        $dateTime = New DateTimeImmutable('2019-11-11 16:01:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(8, $object->getExpiredTime($vacancy, $dateNow));
    }

    /**
     * @throws NoDateException
     */

    public function testGetExpiredTimeThird()
    {
        $this->calc->method('workDaysBetweenDates')->willReturn(0);
        $object = new VacancyListEntryDTOBuilder($this->calc);
        $vacancy = new Vacancy();
        $dateNow = new DateTime();
        $dateTime = New DateTimeImmutable('2019-11-11 01:01:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(24, $object->getExpiredTime($vacancy, $dateNow));
    }
}