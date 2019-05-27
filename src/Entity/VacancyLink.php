<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacancyLinkRepository")
 */
class VacancyLink
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
    private $link;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $letterText;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacancy", inversedBy="vacancyLinks")
     * @ORM\JoinColumn
     */
    private $vacancy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandidateLink", mappedBy="vacancyLink")
     */
    private $candidateLinks;


    public function __construct()
    {
        $this->candidateLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLetterText(): ?string
    {
        return $this->letterText;
    }

    public function setLetterText(?string $letterText): self
    {
        $this->letterText = $letterText;

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
            $candidateLink->setVacancyLink($this);
        }

        return $this;
    }

    public function removeCandidateLink(CandidateLink $candidateLink): self
    {
        if ($this->candidateLinks->contains($candidateLink)) {
            $this->candidateLinks->removeElement($candidateLink);
            // set the owning side to null (unless already changed)
            if ($candidateLink->getVacancyLink() === $this) {
                $candidateLink->setVacancyLink(null);
            }
        }

        return $this;
    }
}
