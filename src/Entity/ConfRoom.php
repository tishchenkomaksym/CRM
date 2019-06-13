<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfRoomRepository")
 */
class ConfRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Office", inversedBy="confRooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $office;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandidateVacancy", mappedBy="confRoom")
     */
    private $candidateVacancies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandidateLink", mappedBy="confRoom")
     */
    private $candidateLinks;

    public function __construct()
    {
        $this->candidateVacancies = new ArrayCollection();
        $this->candidateLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    public function setOffice(?Office $office): self
    {
        $this->office = $office;

        return $this;
    }

    /**
     * @return Collection|CandidateVacancy[]
     */
    public function getCandidateVacancies(): Collection
    {
        return $this->candidateVacancies;
    }

    public function addCandidateVacancy(CandidateVacancy $candidateVacancy): self
    {
        if (!$this->candidateVacancies->contains($candidateVacancy)) {
            $this->candidateVacancies[] = $candidateVacancy;
            $candidateVacancy->setConfRoom($this);
        }

        return $this;
    }

    public function removeCandidateVacancy(CandidateVacancy $candidateVacancy): self
    {
        if ($this->candidateVacancies->contains($candidateVacancy)) {
            $this->candidateVacancies->removeElement($candidateVacancy);
            // set the owning side to null (unless already changed)
            if ($candidateVacancy->getConfRoom() === $this) {
                $candidateVacancy->setConfRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CandidateLink[]
     */
    public function getCandidateLinks(): Collection
    {
        return $this->candidateLinks;
    }

    public function addCandidateLink(CandidateLink $candidateLink): self
    {
        if (!$this->candidateLinks->contains($candidateLink)) {
            $this->candidateLinks[] = $candidateLink;
            $candidateLink->setConfRoom($this);
        }

        return $this;
    }

    public function removeCandidateLink(CandidateLink $candidateLink): self
    {
        if ($this->candidateLinks->contains($candidateLink)) {
            $this->candidateLinks->removeElement($candidateLink);
            // set the owning side to null (unless already changed)
            if ($candidateLink->getConfRoom() === $this) {
                $candidateLink->setConfRoom(null);
            }
        }

        return $this;
    }
}
