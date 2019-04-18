<?php


namespace App\Service\Vacancy\Display\ListEntry;

use App\Entity\Vacancy;

class VacancyLogicManyHolidays implements ExpiredTimeInterface
{

    public function expiredTime(Vacancy $vacancy): int
    {
        return 24;
    }
}