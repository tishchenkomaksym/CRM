<?php


namespace App\Service\Vacancy\CreateCandidateVacancyLinkForLetter;


use App\Entity\CandidateLink;
use App\Entity\Vacancy;

class CandidateLinkProvider implements CandidateLinkVacancyInterface
{
    /**
     * @var CandidateLink
     */
    private $candidateLink;

    public function __construct(CandidateLink $candidateLink)
    {

        $this->candidateLink = $candidateLink;
    }

    public function vacancyId():int
    {
        return $this->candidateLink->getVacancyLink()->getVacancy()->getId();
    }

    public function vacancy():Vacancy
    {
        return $this->candidateLink->getVacancyLink()->getVacancy();
    }

    public function confRoom():string
    {
        return $this->candidateLink->getConfRoom()->getName();
    }

    public function candidateName():string
    {
        return $this->candidateLink->getCandidate()->getName();
    }

    public function candidateSurname():string
    {
        return $this->candidateLink->getCandidate()->getSurname();
    }

    public function departmentManagerEmail():string
    {
        return $this->candidateLink->getCreatedBy()->getEmail();
    }

    public function viewerEmail():string
    {
        return $this->candidateLink->getVacancyLink()->getVacancy()->getVacancyViewerUser()->getUser()->getEmail();
    }

    public function dateInterview()
    {
        return $this->candidateLink->getDateInterview();
    }

    public function recruiterEmail()
    {
        return $this->candidateLink->getCreatedBy()->getEmail();
    }
}