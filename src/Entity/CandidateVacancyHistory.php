<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateVacancyHistoryRepository")
 */
class CandidateVacancyHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateLink", inversedBy="candidateVacancyHistories")
     */
    private $candidateLink;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateVacancy", inversedBy="candidateVacancyHistories")
     */
    private $candidateVacancy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string $type
     *
     * @ORM\Column(name="candidateStatus", nullable=true, type="string", length=255, columnDefinition="ENUM('CV Received','Candidate is interested in vacancy', 'Candidate is waiting for approval', 'Approved for the interview','Interview timing specification','Waiting for interview','Waiting for our final response','Closed by recrutier','Closed by department manager','Candidate declined proposition')")
     */
    private $candidateStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidateLink(): ?CandidateLink
    {
        return $this->candidateLink;
    }

    public function setCandidateLink(?CandidateLink $candidateLink): self
    {
        $this->candidateLink = $candidateLink;

        return $this;
    }

    public function getCandidateVacancy(): ?CandidateVacancy
    {
        return $this->candidateVacancy;
    }

    public function setCandidateVacancy(?CandidateVacancy $candidateVacancy): self
    {
        $this->candidateVacancy = $candidateVacancy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCandidateStatus(): ?string
    {
        return $this->candidateStatus;
    }

    public function setCandidateStatus(string $candidateStatus): self
    {
        $this->candidateStatus = $candidateStatus;

        return $this;
    }
}
