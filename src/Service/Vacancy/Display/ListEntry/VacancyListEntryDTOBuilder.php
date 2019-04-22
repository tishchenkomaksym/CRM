<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Repository\VacancyRepository;
use App\Service\WorkingDays\BaseWorkingDaysCalculator;
use DateTime;

class VacancyListEntryDTOBuilder
{

    /**
     * @var BaseWorkingDaysCalculator
     */
    private $hoursInformation;
    private $lastDateHours;
    private $lastSettingHour;

    public function __construct(BaseWorkingDaysCalculator $hoursInformation)
    {
        $this->hoursInformation = $hoursInformation;

    }

    /**
     * @param VacancyRepository $vacancy
     * @return VacancyListEntryDTO
     * @throws NoDateException
     * @throws \Exception
     */

    public function build(Vacancy $vacancy): VacancyListEntryDTO
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
        $object->createdAt = $vacancy->getCreatedAt();
        $object->expiredTimeNoAssignee = $this->getExpiredTimeNoAssignee($vacancy, new DateTime());
        if ($vacancy->getAssigneeDate() != null) {
            $object->expiredTimeAssignee = $this->getExpiredTimeAssignee($vacancy, new DateTime());
        }

        return $object;
    }

    /**
     * @param Vacancy $vacancy
     * @param DateTime $dateNow
     * @return float
     * @throws NoDateException
     */

    public function getExpiredTimeAssignee(Vacancy $vacancy, DateTime $dateNow): float
    {

        if ($vacancy->getAssigneeDate() === null) {
            throw new NoDateException('No time  assignee of vacancy');

        }

        $approve = new \DateTime("@{$vacancy->getAssigneeDate()->getTimeStamp()}");

        $quantityDays = $this->hoursInformation->getWorkingDaysBetweenDatesNumbers($approve, $dateNow);
        $approveHours = $vacancy->getAssigneeDate()->format('H');
        $allDaysCount = $dateNow->diff($approve)->d + 1;

        $dateHours = $dateNow->format('H');

        if ($approveHours <= 18 && $approveHours >= 9){
            $this->lastSettingHour = $approveHours - 9.5;
        }

        if ($dateHours <= 18 && $dateHours >= 9){
            $this->lastDateHours = 18 - $dateHours;
        }


        if ($quantityDays === 0) {
            return 0;
        }

        if ($allDaysCount !== $quantityDays && $this->hoursInformation->getWorkingDaysBetweenDatesNumbers($approve, $dateNow) === 1)  {

            if ($this->hoursInformation->getWorkingDaysBetweenDatesNumbers($approve, $approve) === 0) {

                return 8.5 - $this->lastDateHours;

            }


            return 8.5 - $this->lastSettingHour;

         }

        if ($this->hoursInformation->getWorkingDaysBetweenDatesNumbers($approve, $dateNow) === 1){

            return 8.5 - $this->lastSettingHour - $this->lastDateHours;
        }


        return $quantityDays * 8.5 - $this->lastSettingHour - $this->lastDateHours;

    }

    /**
     * @param Vacancy $vacancy
     * @param DateTime $dateNow
     * @return float
     * @throws NoDateException
     */
    public function getExpiredTimeNoAssignee(Vacancy $vacancy, DateTime $dateNow): float
    {

        if ($vacancy->getCreatedAt() === null) {
            throw new NoDateException('No date created of vacancy');

        }

        $create = new \DateTime("@{$vacancy->getCreatedAt()->getTimeStamp()}");

        $quantityDays = $this->hoursInformation->getWorkingDaysBetweenDatesNumbers($create, $dateNow);
        $createHours = $vacancy->getCreatedAt()->format('H');
        $allDaysCount = $dateNow->diff($create)->d + 1;

        $dateHours = $dateNow->format('H');

        if ($createHours <= 18 && $createHours >= 9){
            $this->lastSettingHour = $createHours - 9.5;
        }

        if ($dateHours <= 18 && $dateHours >= 9){
            $this->lastDateHours = 18 - $dateHours;
        }


        if ($quantityDays === 0) {
            return 0;
        }

        if ($allDaysCount !== $quantityDays && $this->hoursInformation->getWorkingDaysBetweenDatesNumbers($create, $dateNow) === 1)  {

            if ($this->hoursInformation->getWorkingDaysBetweenDatesNumbers($create, $create) === 0) {

                return 8.5 - $this->lastDateHours;

            }


            return 8.5 - $this->lastSettingHour;

        }

        if ($this->hoursInformation->getWorkingDaysBetweenDatesNumbers($create, $dateNow) === 1){

            return 8.5 - $this->lastSettingHour - $this->lastDateHours;
        }


        return $quantityDays * 8.5 - $this->lastSettingHour - $this->lastDateHours;

    }

}