<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateLinkRepository")
 */
class CandidateLink
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Candidate", inversedBy="candidateLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VacancyLink", inversedBy="candidateLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacancyLink;

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
     * @ORM\Column(name="candidateStatus", nullable=true, type="string", length=255, columnDefinition="ENUM('CV Received','Candidate is interested in vacancy', 'Candidate is waiting for approval', 'Approved for the interview','Interview timing specification', 'Interview', 'Contract Concluding', 'Start date of new employee is set', 'New employee onboarding', 'Waiting for interview','Waiting for our final response','Closed by recrutier','Closed by department manager','Closed. Candidate declined proposition', 'Employed')")
     */
    private $candidateStatus;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $denialInterview;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentViewer", mappedBy="candidateLink")
     */
    private $commentViewers;

    public function __construct()
    {
        $this->commentViewers = new ArrayCollection();
        $this->candidateVacancyHistories = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandidateVacancyHistory", mappedBy="candidateLink")
     */
    private $candidateVacancyHistories;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateInterview;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfRoom", inversedBy="candidateLinks")
     */
    private $confRoom;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateManagerApproval", mappedBy="candidateLink", cascade={"persist", "remove"})
     */
    private $candidateManagerApproval;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateManagerDeny", mappedBy="candidateLink", cascade={"persist", "remove"})
     */
    private $candidateManagerDeny;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateStartWork;



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

    public function getVacancyLink(): ?VacancyLink
    {
        return $this->vacancyLink;
    }

    public function setVacancyLink(?VacancyLink $vacancyLink): self
    {
        $this->vacancyLink = $vacancyLink;

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

    public function setReceivedCv($receivedCv): self
    {
        $this->receivedCv = $receivedCv;

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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|CandidateVacancyHistory[]
     */
    public function getCandidateVacancyHistories(): Collection
    {
        return $this->candidateVacancyHistories;
    }

    public function addCandidateVacancyHistory(CandidateVacancyHistory $candidateVacancyHistory): self
    {
        if (!$this->candidateVacancyHistories->contains($candidateVacancyHistory)) {
            $this->candidateVacancyHistories[] = $candidateVacancyHistory;
            $candidateVacancyHistory->setCandidateLink($this);
        }

        return $this;
    }

    public function removeCandidateVacancyHistory(CandidateVacancyHistory $candidateVacancyHistory): self
    {
        if ($this->candidateVacancyHistories->contains($candidateVacancyHistory)) {
            $this->candidateVacancyHistories->removeElement($candidateVacancyHistory);
            // set the owning side to null (unless already changed)
            if ($candidateVacancyHistory->getCandidateLink() === $this) {
                $candidateVacancyHistory->setCandidateLink(null);
            }
        }

        return $this;
    }


    public function getDenialInterview(): ?string
    {
        return $this->denialInterview;
    }

    public function setDenialInterview(?string $denialInterview): self
    {
        $this->denialInterview = $denialInterview;

        return $this;
    }

    /**
     * @return Collection|CommentViewer[]
     */
    public function getCommentViewers(): Collection
    {
        return $this->commentViewers;
    }

    public function addCommentViewer(CommentViewer $commentViewer): self
    {
        if (!$this->commentViewers->contains($commentViewer)) {
            $this->commentViewers[] = $commentViewer;
            $commentViewer->setCandidateLink($this);
        }

        return $this;
    }

    public function removeCommentViewer(CommentViewer $commentViewer): self
    {
        if ($this->commentViewers->contains($commentViewer)) {
            $this->commentViewers->removeElement($commentViewer);
            // set the owning side to null (unless already changed)
            if ($commentViewer->getCandidateLink() === $this) {
                $commentViewer->setCandidateLink(null);
            }
        }

        return $this;
    }

    public function getDateInterview(): ?\DateTimeInterface
    {
        return $this->dateInterview;
    }

    public function setDateInterview(?\DateTimeInterface $dateInterview): self
    {
        $this->dateInterview = $dateInterview;

        return $this;
    }

    public function getConfRoom(): ?ConfRoom
    {
        return $this->confRoom;
    }

    public function setConfRoom(?ConfRoom $confRoom): self
    {
        $this->confRoom = $confRoom;

        return $this;
    }

    public function getCandidateManagerApproval(): ?CandidateManagerApproval
    {
        return $this->candidateManagerApproval;
    }

    public function setCandidateManagerApproval(?CandidateManagerApproval $candidateManagerApproval): self
    {
        $this->candidateManagerApproval = $candidateManagerApproval;

        // set (or unset) the owning side of the relation if necessary
        $newCandidateLink = $candidateManagerApproval === null ? null : $this;
        if ($newCandidateLink !== $candidateManagerApproval->getCandidateLink()) {
            $candidateManagerApproval->setCandidateLink($newCandidateLink);
        }

        return $this;
    }

    public function getCandidateManagerDeny(): ?CandidateManagerDeny
    {
        return $this->candidateManagerDeny;
    }

    public function setCandidateManagerDeny(?CandidateManagerDeny $candidateManagerDeny): self
    {
        $this->candidateManagerDeny = $candidateManagerDeny;

        // set (or unset) the owning side of the relation if necessary
        $newCandidateLink = $candidateManagerDeny === null ? null : $this;
        if ($newCandidateLink !== $candidateManagerDeny->getCandidateLink()) {
            $candidateManagerDeny->setCandidateLink($newCandidateLink);
        }

        return $this;
    }

    public function getDateStartWork(): ?\DateTimeInterface
    {
        return $this->dateStartWork;
    }

    public function setDateStartWork(?\DateTimeInterface $dateStartWork): self
    {
        $this->dateStartWork = $dateStartWork;

        return $this;
    }

}
