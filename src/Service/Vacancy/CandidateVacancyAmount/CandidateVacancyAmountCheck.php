<?php


namespace App\Service\Vacancy\CandidateVacancyAmount;


use App\Entity\Vacancy;
use Symfony\Component\Validator\Constraint;

class CandidateVacancyAmountCheck extends Constraint
{
    public $message = 'This vacancy have enough candidates and is already closed';
    /**
     * @var Vacancy
     */
    public $vacancy;


    public function __construct(Vacancy $vacancy)
    {
        parent::__construct([]);
        $this->vacancy = $vacancy;
    }

    /**
     * @return Vacancy
     */
    public function getVacancy(): Vacancy
    {
        return $this->vacancy;
    }
}