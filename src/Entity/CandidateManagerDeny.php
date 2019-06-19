<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateManagerDenyRepository")
 */
class CandidateManagerDeny
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
    private $impression;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $recruiterReported;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateLink", inversedBy="candidateManagerDeny", cascade={"persist", "remove"})
     */
    private $candidateLink;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateVacancy", inversedBy="candidateManagerDeny", cascade={"persist", "remove"})
     */
    private $candidateVacancy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DenyReasonDepartment", mappedBy="candidateManagerDeny")
     */
    private $denyReasonDepartments;


    public function __construct()
    {
        $this->denyReasonDepartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImpression(): ?string
    {
        return $this->impression;
    }

    public function setImpression(?string $impression): self
    {
        $this->impression = $impression;

        return $this;
    }

    public function getRecruiterReported(): ?bool
    {
        return $this->recruiterReported;
    }

    public function setRecruiterReported(?bool $recruiterReported): self
    {
        $this->recruiterReported = $recruiterReported;

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
     * @return Collection|DenyReasonDepartment[]
     */
    public function getDenyReasonDepartments(): Collection
    {
        return $this->denyReasonDepartments;
    }

    public function addDenyReasonDepartment(DenyReasonDepartment $denyReasonDepartment): self
    {
        if (!$this->denyReasonDepartments->contains($denyReasonDepartment)) {
            $this->denyReasonDepartments[] = $denyReasonDepartment;
            $denyReasonDepartment->setCandidateManagerDeny($this);
        }

        return $this;
    }

    public function removeDenyReasonDepartment(DenyReasonDepartment $denyReasonDepartment): self
    {
        if ($this->denyReasonDepartments->contains($denyReasonDepartment)) {
            $this->denyReasonDepartments->removeElement($denyReasonDepartment);
            // set the owning side to null (unless already changed)
            if ($denyReasonDepartment->getCandidateManagerDeny() === $this) {
                $denyReasonDepartment->setCandidateManagerDeny(null);
            }
        }

        return $this;
    }


}
