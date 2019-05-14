<?php


namespace App\Service\Vacancy\VacancyTimeDecorator;


use App\Entity\Vacancy;
use DateTimeImmutable;

class VacancyTimeDecorator
{
    private $vacancy;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function expiredTimeDecorator(): ?DateTimeImmutable
    {
        if ($this->vacancy->getApproveDate() != null) {
            $object = $this->vacancy->getApproveDate();
        } elseif ($this->vacancy->getAssigneeDate() != null){
            $object = $this->vacancy->getAssigneeDate();
        }else{
            $object = new DateTimeImmutable('now');
        }

        return $object;
    }
}