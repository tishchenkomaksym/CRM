<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Entity\Vacancy;
use PHPUnit\Framework\TestCase;


class VacancyLogicManyHolidaysTest extends TestCase
{
    public function testExpiredTime()
    {
        $object = new VacancyLogicManyHolidays();
        $vacancy = new Vacancy();
        $this->assertEquals(24, $object->expiredTime($vacancy));
    }
}