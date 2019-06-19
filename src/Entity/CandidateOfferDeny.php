<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateOfferDenyRepository")
 */
class CandidateOfferDeny
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $noSuitableReason;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $desiredSalary;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateLink", cascade={"persist", "remove"})
     */
    private $candidateLink;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateVacancy", cascade={"persist", "remove"})
     */
    private $candidateVacancy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DenyReasonCandidate", mappedBy="candidateOfferDeny")
     */
    private $denyReasonCandidates;

    public function __construct()
    {
        $this->denyReasonCandidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoSuitableReason(): ?string
    {
        return $this->noSuitableReason;
    }

    public function setNoSuitableReason(?string $noSuitableReason): self
    {
        $this->noSuitableReason = $noSuitableReason;

        return $this;
    }

    public function getDesiredSalary(): ?string
    {
        return $this->desiredSalary;
    }

    public function setDesiredSalary(?string $desiredSalary): self
    {
        $this->desiredSalary = $desiredSalary;

        return $this;
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

    /**
     * @return Collection|DenyReasonCandidate[]
     */
    public function getDenyReasonCandidates(): Collection
    {
        return $this->denyReasonCandidates;
    }

    public function addDenyReasonCandidate(DenyReasonCandidate $denyReasonCandidate): self
    {
        if (!$this->denyReasonCandidates->contains($denyReasonCandidate)) {
            $this->denyReasonCandidates[] = $denyReasonCandidate;
            $denyReasonCandidate->setCandidateOfferDeny($this);
        }

        return $this;
    }

    public function removeDenyReasonCandidate(DenyReasonCandidate $denyReasonCandidate): self
    {
        if ($this->denyReasonCandidates->contains($denyReasonCandidate)) {
            $this->denyReasonCandidates->removeElement($denyReasonCandidate);
            // set the owning side to null (unless already changed)
            if ($denyReasonCandidate->getCandidateOfferDeny() === $this) {
                $denyReasonCandidate->setCandidateOfferDeny(null);
            }
        }

        return $this;
    }

}
