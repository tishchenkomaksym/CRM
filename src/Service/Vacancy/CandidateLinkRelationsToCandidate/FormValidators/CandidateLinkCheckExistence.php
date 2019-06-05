<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;


use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use Symfony\Component\Validator\Constraint;

class CandidateLinkCheckExistence extends Constraint
{
    public $message = 'This candidate was already added';
    /**
     * @var Vacancy
     */
    public $vacancyLink;



    public function __construct(VacancyLink $vacancyLink)
    {
        parent::__construct([]);
        $this->vacancyLink = $vacancyLink;
    }

}