<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidateManagerApprovalRepository")
 */
class CandidateManagerApproval
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="candidateManagerApprovals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Column(type="text")
     */
    private $directionEnterpreneur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $startDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $salary;

    /**
     * @ORM\Column(type="text")
     */
    private $workPlace;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateLink", inversedBy="candidateManagerApproval", cascade={"persist", "remove"})
     */
    private $candidateLink;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CandidateVacancy", inversedBy="candidateManagerApproval", cascade={"persist", "remove"})
     */
    private $candidateVacancy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getDirectionEnterpreneur(): ?string
    {
        return $this->directionEnterpreneur;
    }

    public function setDirectionEnterpreneur(string $directionEnterpreneur): self
    {
        $this->directionEnterpreneur = $directionEnterpreneur;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getWorkPlace(): ?string
    {
        return $this->workPlace;
    }

    public function setWorkPlace(string $workPlace): self
    {
        $this->workPlace = $workPlace;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

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
}
