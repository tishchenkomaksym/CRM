<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Entity\Vacancy;

interface ExpiredTimeInterface
{
    public function expiredTime(Vacancy $vacancy): int;
}