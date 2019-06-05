<?php


namespace App\Service\Vacancy\CandidateApprove;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use App\Entity\CommentViewer;
use App\Entity\Vacancy;

class DepartmentManagerApproveViewDTO
{
    /**
     * @var Candidate
     */
    private $candidate;
    /**
     * @var Vacancy
     */
    private $vacancy;
    /**
     * @var CandidateVacancy|null
     */
    private $candidateVacancy;

    /**
     * @var CandidateLink|null
     */
    private $candidateLink;


    /**
     * @var CommentViewer|null
     */
    private $checkCandidateLink;

    /**
     * @var CommentViewer|null
     */
    private $checkCandidateVacancy;

    /**
     * @return Candidate
     */
    public function getCandidate(): Candidate
    {
        return $this->candidate;
    }

    /**
     * @param Candidate $candidate
     * @return DepartmentManagerApproveViewDTO
     */
    public function setCandidate(Candidate $candidate): DepartmentManagerApproveViewDTO
    {
        $this->candidate = $candidate;
        return $this;
    }

    /**
     * @return Vacancy
     */
    public function getVacancy(): Vacancy
    {
        return $this->vacancy;
    }

    /**
     * @param Vacancy $vacancy
     * @return DepartmentManagerApproveViewDTO
     */
    public function setVacancy(Vacancy $vacancy): DepartmentManagerApproveViewDTO
    {
        $this->vacancy = $vacancy;
        return $this;
    }

    /**
     * @return CommentViewer|null
     */
    public function getCheckCandidateLink(): ?CommentViewer
    {
        return $this->checkCandidateLink;
    }

    /**
     * @param CommentViewer|null $checkCandidateLink
     */
    public function setCheckCandidateLink(?CommentViewer $checkCandidateLink): void
    {
        $this->checkCandidateLink = $checkCandidateLink;
    }

    /**
     * @return CommentViewer|null
     */
    public function getCheckCandidateVacancy(): ?CommentViewer
    {
        return $this->checkCandidateVacancy;
    }

    /**
     * @param CommentViewer|null $checkCandidateVacancy
     */
    public function setCheckCandidateVacancy(?CommentViewer $checkCandidateVacancy): void
    {
        $this->checkCandidateVacancy = $checkCandidateVacancy;
    }

    /**
     * @return CandidateVacancy|null
     */
    public function getCandidateVacancy(): ?CandidateVacancy
    {
        return $this->candidateVacancy;
    }

    /**
     * @param CandidateVacancy|null $candidateVacancy
     */
    public function setCandidateVacancy(?CandidateVacancy $candidateVacancy): void
    {
        $this->candidateVacancy = $candidateVacancy;
    }

    /**
     * @return CandidateLink|null
     */
    public function getCandidateLink(): ?CandidateLink
    {
        return $this->candidateLink;
    }

    /**
     * @param CandidateLink|null $candidateLink
     */
    public function setCandidateLink(?CandidateLink $candidateLink): void
    {
        $this->candidateLink = $candidateLink;
    }


}