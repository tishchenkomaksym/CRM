<?php


namespace App\Service\Vacancy\CreateCandidateVacancyLinkForLetter;


use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;

class CandidateVacancyProvider implements CandidateLinkVacancyInterface
{
    /**
     * @var CandidateVacancy
     */
    private $candidateVacancy;

    public function __construct(CandidateVacancy $candidateVacancy)
    {

        $this->candidateVacancy = $candidateVacancy;
    }

    public function vacancyId():int
    {
        return $this->candidateVacancy->getVacancy()->getId();
    }

    public function vacancy():Vacancy
    {
        return $this->candidateVacancy->getVacancy();
    }

    public function confRoom():string
    {
        return $this->candidateVacancy->getConfRoom()->getName();
    }

    public function candidateName():string
    {
        return $this->candidateVacancy->getCandidate()->getName();
    }

    public function candidateSurname():string
    {
        return $this->candidateVacancy->getCandidate()->getSurname();
    }

    public function departmentManagerEmail():string
    {
        return $this->candidateVacancy->getCreatedBy()->getEmail();
    }

    public function viewerEmail():string
    {
        return $this->candidateVacancy->getVacancy()->getVacancyViewerUser()->getEmail();
    }

    public function dateInterview()
    {
        return $this->candidateVacancy->getDateInterview();
    }

    public function recruiterEmail()
    {
        return $this->candidateVacancy->getCreatedBy()->getEmail();
    }
}