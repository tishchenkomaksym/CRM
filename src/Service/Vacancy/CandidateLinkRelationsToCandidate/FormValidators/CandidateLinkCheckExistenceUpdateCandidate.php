<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;

use Symfony\Component\Validator\Constraint;

class CandidateLinkCheckExistenceUpdateCandidate extends Constraint
{

    public $message = 'This vacancy was already added';

    public function __construct()
    {
        parent::__construct([]);
    }
}