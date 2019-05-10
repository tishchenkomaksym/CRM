<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateRepository")
 */
class Candidate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedIn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $experience;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $education;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $employment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkToCv;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandidateVacancy", mappedBy="candidate")
     */
    private $candidateVacancies;

    public function __construct()
    {
        $this->candidateVacancies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;
        return $this;
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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLinkedIn(): ?string
    {
        return $this->linkedIn;
    }

    public function setLinkedIn(?string $linkedIn): self
    {
        $this->linkedIn = $linkedIn;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    public function getEmployment(): ?string
    {
        return $this->employment;
    }

    public function setEmployment(?string $employment): self
    {
        $this->employment = $employment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getUpdatedDate(): ?DateTime
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?DateTime $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

//    public function getLink()
//    {
//        return $this->link;
//    }
//
//    public function setLink($link): self
//    {
//        $this->link = $link;
//
//        return $this;
//    }


    public function getLinkToCv(): ?string
    {
        return $this->linkToCv;
    }


    public function setLinkToCv(?string $linkToCv): self
    {
        $this->linkToCv = $linkToCv;

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
            $candidateVacancy->setCandidate($this);
        }

        return $this;
    }

    public function removeCandidateVacancy(CandidateVacancy $candidateVacancy): self
    {
        if ($this->candidateVacancies->contains($candidateVacancy)) {
            $this->candidateVacancies->removeElement($candidateVacancy);
            // set the owning side to null (unless already changed)
            if ($candidateVacancy->getCandidate() === $this) {
                $candidateVacancy->setCandidate(null);
            }
        }

        return $this;
    }
}
