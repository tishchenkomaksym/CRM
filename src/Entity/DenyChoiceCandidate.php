<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DenyChoiceCandidateRepository")
 */
class DenyChoiceCandidate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DenyReasonCandidate", mappedBy="denyChoiceCandidate")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $denyReasonCandidate->setDenyChoiceCandidate($this);
        }

        return $this;
    }

    public function removeDenyReasonCandidate(DenyReasonCandidate $denyReasonCandidate): self
    {
        if ($this->denyReasonCandidates->contains($denyReasonCandidate)) {
            $this->denyReasonCandidates->removeElement($denyReasonCandidate);
            // set the owning side to null (unless already changed)
            if ($denyReasonCandidate->getDenyChoiceCandidate() === $this) {
                $denyReasonCandidate->setDenyChoiceCandidate(null);
            }
        }

        return $this;
    }
}
