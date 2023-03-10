<?php


namespace App\Service\Vacancy\Display\ListEntry;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use DateTime;
use Exception;

class VacancyListEntryDTOBuilder
{
    /**
     * @var ExpiredTimeCalculator
     */
    private $calculator;

    public function __construct(ExpiredTimeCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param Vacancy $vacancy
     * @return VacancyListEntryDTO
     * @throws NoDateException
     * @throws Exception
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
        $object->status = $vacancy->getStatus();
        $object->createdAt = $vacancy->getCreatedAt();
        $object->updatedDate = $vacancy->getUpdatedDate();

        if ($vacancy->getApproveDate() != null) {
            $object->expiredTimeApprove = $this->calculator->getExpiredTime($vacancy->getApproveDate(), new DateTime());
        }
        if ($vacancy->getAssigneeDate() != null){
            $object->expiredTimeAssignee = $this->calculator->getExpiredTime($vacancy->getAssigneeDate(), new DateTime());
        }

        return $object;
    }

}