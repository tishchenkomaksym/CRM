<?php


namespace App\Service\Vacancy\CreateCandidateVacancyLinkForLetter;


use App\Entity\Candidate;
use App\Entity\CandidateManagerApproval;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use DateTimeImmutable;
use DateTimeInterface;

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

    /**
     * @return Vacancy
     */
    public function vacancy():Vacancy
    {
        return $this->candidateVacancy->getVacancy();
    }

    public function vacancyApproveDate(): DateTimeImmutable
    {
        return $this->vacancy()->getApproveDate();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function vacancyApprovedByEmail():string
    {
        if ($this->vacancy()->getApprovedBy() === null){
            throw new NoDataException('Approved By not Found');
        }
        return $this->vacancy()->getApprovedBy()->getEmail();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function vacancyAssignedToEmail():string
    {
        if ($this->vacancy()->getAssignee() === null){
            throw new NoDataException('AssigneeBy not Found');
        }
        return $this->vacancy()->getAssignee()->getEmail();
    }

    public function vacancyCreatedAt(): DateTimeImmutable
    {
        return $this->vacancy()->getCreatedAt();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function confRoom(): string
    {
        if ($this->candidateVacancy->getConfRoom() === null){
            throw new NoDataException('ConfRoom not Found');
        }
        return $this->candidateVacancy->getConfRoom()->getName();
    }

    public function candidate():Candidate
    {
        return $this->candidateVacancy->getCandidate();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function departmentManagerEmail(): string
    {
        if ($this->vacancy()->getCreatedBy() === null){
            throw new NoDataException('Vacancy CreatedBy not Found');
        }
        return $this->vacancy()->getCreatedBy()->getEmail();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function viewerEmail(): string
    {
        if ($this->vacancy()->getVacancyViewerUser() === null){
            throw new NoDataException('VacancyViewer not Found');
        }
        if ($this->vacancy()->getVacancyViewerUser()->getUser() === null){
            throw new NoDataException('User not Found');
        }
        return $this->vacancy()->getVacancyViewerUser()->getUser()->getEmail();
    }

    public function dateInterview():?DateTimeInterface
    {
        return $this->candidateVacancy->getDateInterview();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function recruiterEmail():string
    {
        if ($this->candidateVacancy->getCreatedBy() === null){
            throw new NoDataException('CandidateLink CreatedBy not Found');
        }
        return $this->candidateVacancy->getCreatedBy()->getEmail();
    }

    public function candidateManagerApproval():CandidateManagerApproval
    {
        return $this->candidateVacancy->getCandidateManagerApproval();
    }

    public function dateStartWork(): DateTimeInterface
    {
        return $this->candidateVacancy->getDateStartWork();
    }
}