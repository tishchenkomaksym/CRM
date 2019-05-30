<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateVacancyRepository")
 */
class CandidateVacancy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Candidate", inversedBy="candidateVacancies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacancy", inversedBy="candidateVacancies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacancy;

    /**
     * @var string $type
     *
     * @ORM\Column(name="candidateFrom", nullable=true, type="string", length=255, columnDefinition="ENUM('vacancy', 'hunting','recommendation')")
     */
    private $candidateFrom;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $receivedCv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkToProfile1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkToProfile2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkToProfile3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkToProfile4;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentInterest;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $denialReason;

    /**
     * @var string $type
     *
     * @ORM\Column(name="candidateStatus", nullable=true, type="string", length=255, columnDefinition="ENUM('CV Received','Candidates Interest is checked','Waiting for response','Approved for the interview','Interview timing specification','Waiting for interview','Waiting for our final response','Closed')")
     */
    private $candidateStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getVacancy(): ?Vacancy
    {
        return $this->vacancy;
    }

    public function setVacancy(?Vacancy $vacancy): self
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    public function getCandidateFrom(): ?string
    {
        return $this->candidateFrom;
    }

    public function setCandidateFrom(?string $candidateFrom): self
    {
        $this->candidateFrom = $candidateFrom;

        return $this;
    }

    public function getReceivedCv()
    {
        return $this->receivedCv;
    }

    public function setReceivedCv($receivedCv)
    {
        $this->receivedCv = $receivedCv;

        return $this;
    }

    public function getLinkToProfile1(): ?string
    {
        return $this->linkToProfile1;
    }

    public function setLinkToProfile1(?string $linkToProfile1): self
    {
        $this->linkToProfile1 = $linkToProfile1;

        return $this;
    }

    public function getLinkToProfile2(): ?string
    {
        return $this->linkToProfile2;
    }

    public function setLinkToProfile2(?string $linkToProfile2): self
    {
        $this->linkToProfile2 = $linkToProfile2;

        return $this;
    }

    public function getLinkToProfile3(): ?string
    {
        return $this->linkToProfile3;
    }

    public function setLinkToProfile3(?string $linkToProfile3): self
    {
        $this->linkToProfile3 = $linkToProfile3;

        return $this;
    }

    public function getLinkToProfile4(): ?string
    {
        return $this->linkToProfile4;
    }

    public function setLinkToProfile4(?string $linkToProfile4): self
    {
        $this->linkToProfile4 = $linkToProfile4;

        return $this;
    }

    public function getCommentInterest(): ?string
    {
        return $this->commentInterest;
    }

    public function setCommentInterest(?string $commentInterest): self
    {
        $this->commentInterest = $commentInterest;

        return $this;
    }

    public function getDenialReason(): ?string
    {
        return $this->denialReason;
    }

    public function setDenialReason(?string $denialReason): self
    {
        $this->denialReason = $denialReason;

        return $this;
    }

    public function getCandidateStatus(): ?string
    {
        return $this->candidateStatus;
    }

    public function setCandidateStatus(?string $candidateStatus): self
    {
        $this->candidateStatus = $candidateStatus;

        return $this;
    }
}