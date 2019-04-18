<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;

class VacancyLogicWorkingDayAndHoliday implements ExpiredTimeInterface
{
    /**
     * @var BaseWorkingDaysCalculator
     */
    private $hoursInformation;

    public function __construct(BaseWorkingDaysCalculator $hoursInformation)
    {

        $this->hoursInformation = $hoursInformation;
    }

    /**
     * @param Vacancy $vacancy
     * @return int
     * @throws NoDateException
     */
    public function expiredTime(Vacancy $vacancy): int
    {

        $dateMinus = new DateTime('-1 days');
        $timeMinus = $dateMinus->setTimestamp($dateMinus->getTimestamp());

//        $approve = new \DateTime("@{$vacancy->getApproveDate()->getTimeStamp()}");
       $dateTime = new \DateTime();
        $approve = $dateTime->setTimestamp($vacancy->getApproveDate()->getTimestamp());

        if ($vacancy->getApproveDate() === null) {
            throw new NoDateException('Error, no approve date of vacancy');
        }

        if ($this->hoursInformation->workDaysBetweenDates($approve, $timeMinus) === 1) {
            $approveHours = $vacancy->getApproveDate()->format('H');

            return 24 - $approveHours;

        }
        return 24;
    }
}