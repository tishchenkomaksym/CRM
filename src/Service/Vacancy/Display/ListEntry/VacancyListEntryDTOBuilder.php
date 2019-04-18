<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;

class VacancyListEntryDTOBuilder
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
     * @return VacancyListEntryDTO
     * @throws NoDateException
     */

    public function build(Vacancy $vacancy
    ): VacancyListEntryDTO
    {
        $object = new VacancyListEntryDTO();
        $object->id = $vacancy->getId();
        if ($vacancy->getTeam() === null) {
            throw new NoDateException('Wrong configuration');
        }

        $object->team = $vacancy->getTeam()->getName();
        $object->position = $vacancy->getPosition();
        $object->amount = $vacancy->getAmount();
        $object->approveDate = $vacancy->getApproveDate();
        $object->assignee = $vacancy->getAssignee();
        $object->isApproved = $vacancy->getIsApproved();
        $object->expiredTime = $this->getExpiredTime($vacancy, new DateTime());

        return $object;
    }

    /**
     * @param Vacancy $vacancy
     * @param DateTime $dateNow
     * @return int
     * @throws NoDateException
     */
    public function getExpiredTime(Vacancy $vacancy, DateTime $dateNow): int
    {
        $approve = new \DateTime("@{$vacancy->getApproveDate()->getTimeStamp()}");
        if ($this->hoursInformation->workDaysBetweenDates($approve, $dateNow) >= 2) {
            /** @var ExpiredTimeInterface $hoursCalculator */
            $hoursCalculator = new VacancyLogicManyWorkingDays();
        } elseif ($this->hoursInformation->workDaysBetweenDates($approve, $dateNow) === 0) {
            $hoursCalculator = new VacancyLogicManyHolidays();
        } else {
            $hoursCalculator = new VacancyLogicWorkingDayAndHoliday($this->hoursInformation);
        }
        return $hoursCalculator->expiredTime($vacancy);
    }

}