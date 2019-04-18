<?php

namespace App\Service\Vacancy\Display\ListEntry;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class VacancyLogicManyWorkingDaysTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testExpiredTime()
    {
        $object = new VacancyLogicManyWorkingDays();
        $vacancy = new Vacancy();
        $dateTime = New DateTimeImmutable('2019-11-11 01:01:01');
        $vacancy->setApproveDate($dateTime);
        $this->assertEquals(23, $object->expiredTime($vacancy));
    }


    /**
     * @throws \Exception
     */
    public function testExpiredTimeException()
    {
        $object = new VacancyLogicManyWorkingDays();
        $vacancy = new Vacancy();
        $this->expectException(NoDateException::class);
        $object->expiredTime($vacancy);
    }
}
