<?php


namespace App\Service\Vacancy\CandidateApproveAfterInterview\FormValidatorsAfterInterview;


use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use Symfony\Component\Validator\Constraint;

class CandidateManagerApprovalCheckExistence extends Constraint
{
    public $message = 'This candidate was already approved or denied';
    /**
     * @var CandidateLink
     */
    public $candidateLink;
    /**
     * @var CandidateVacancy
     */
    public $candidateVacancy;


    public function __construct($candidateLink, $candidateVacancy)
    {
        parent::__construct([]);

        $this->candidateLink = $candidateLink;
        $this->candidateVacancy = $candidateVacancy;
    }
}