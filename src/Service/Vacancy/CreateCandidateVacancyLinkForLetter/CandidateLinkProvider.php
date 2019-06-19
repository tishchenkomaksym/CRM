<?php


namespace App\Service\Vacancy\CreateCandidateVacancyLinkForLetter;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\Vacancy;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use DateTimeImmutable;
use DateTimeInterface;

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

    /**
     * @return Vacancy
     * @throws NoDataException
     */
    public function vacancy():Vacancy
    {
        if ($this->candidateLink->getVacancyLink() === null){
            throw new NoDataException('VacancyLink not found');
        }
        return $this->candidateLink->getVacancyLink()->getVacancy();
    }

    /**
     * @return DateTimeImmutable
     * @throws NoDataException
     */
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

    /**
     * @return DateTimeImmutable
     * @throws NoDataException
     */
    public function vacancyCreatedAt(): DateTimeImmutable
    {
        return $this->vacancy()->getCreatedAt();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function confRoom():string
    {
        if ($this->candidateLink->getConfRoom() === null){
            throw new NoDataException('ConfRoom not Found');
        }
        return $this->candidateLink->getConfRoom()->getName();
    }

    public function candidate():Candidate
    {
        return $this->candidateLink->getCandidate();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function departmentManagerEmail():string
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
    public function viewerEmail():string
    {
        if ($this->vacancy()->getVacancyViewerUser() === null){
            throw new NoDataException('VacancyViewer not Found');
        }
        if ($this->vacancy()->getVacancyViewerUser()->getUser() === null){
            throw new NoDataException('User not Found');
        }
        return $this->vacancy()->getVacancyViewerUser()->getUser()->getEmail();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function dateInterview():?DateTimeInterface
    {
        return $this->candidateLink->getDateInterview();
    }

    /**
     * @return string
     * @throws NoDataException
     */
    public function recruiterEmail():string
    {
        if ($this->candidateLink->getCreatedBy() === null){
            throw new NoDataException('CandidateLink CreatedBy not Found');
        }
        return $this->candidateLink->getCreatedBy()->getEmail();
    }
}