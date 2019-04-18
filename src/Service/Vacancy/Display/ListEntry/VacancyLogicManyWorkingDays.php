<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use Exception;

class VacancyLogicManyWorkingDays implements ExpiredTimeInterface
{

    /**
     * @param Vacancy $vacancy
     * @return int
     * @throws Exception
     */
    public function expiredTime(Vacancy $vacancy): int
    {
        if ($vacancy->getApproveDate() === null){
             throw new NoDateException('No approve date of vacancy');
        }
        $approveHours = $vacancy->getApproveDate()->format('H');
        return 24 - $approveHours;

    }
}