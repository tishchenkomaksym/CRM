<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;

use Symfony\Component\Validator\Constraint;

class CandidateVacancyCheckExistenceUpdateCandidate extends Constraint
{

    public $message = 'This candidate was already added';

    public function __construct()
    {
        parent::__construct([]);
    }
}