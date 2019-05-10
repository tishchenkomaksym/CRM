<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;


use App\Entity\Vacancy;
use Symfony\Component\Validator\Constraint;

class CandidateVacancyCheckExistence extends Constraint
{
    public $message = 'This vacancy was already added';
    /**
     * @var Vacancy
     */
    public $vacancy;



    public function __construct(Vacancy $vacancy)
    {
        parent::__construct([]);
        $this->vacancy = $vacancy;
    }

}