<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(name="candidateStatus", nullable=true, type="string", length=255, columnDefinition="ENUM('CV Received','Candidate is interested in vacancy', 'Candidate is waiting for approval', 'Approved for the interview','Interview timing specification','Waiting for interview','Waiting for our final response','Closed by recrutier','Closed by department manager','Candidate declined proposition')")
     */
    private $candidateStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $denialInterview;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentViewer", mappedBy="candidateVacancy")
     */
    private $commentViewers;

    public function __construct()
    {
        $this->commentViewers = new ArrayCollection();
    }

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
            $commentViewer->setCandidateVacancy($this);
        }

        return $this;
    }

    public function removeCommentViewer(CommentViewer $commentViewer): self
    {
        if ($this->commentViewers->contains($commentViewer)) {
            $this->commentViewers->removeElement($commentViewer);
            // set the owning side to null (unless already changed)
            if ($commentViewer->getCandidateVacancy() === $this) {
                $commentViewer->setCandidateVacancy(null);
            }
        }

        return $this;
    }
}
