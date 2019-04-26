<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacancyRepository")
 */
class Vacancy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Office")
     * @ORM\JoinColumn(nullable=false)
     */
    private $office;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department")
     * @ORM\JoinColumn(nullable=false)
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $test;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $english;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bonus;

    /**
     * @ORM\Column(type="text")
     */
    private $responsibilities;

    /**
     * @ORM\Column(type="text")
     */
    private $requirements;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $plus;

    /**
     * @ORM\Column(type="text")
     */
    private $reason;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reasonDenied;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isApproved;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="vacancies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;


    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $approveDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VacancyViewerUser", inversedBy="vacancies")
     */
    private $vacancyViewerUser;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $assigneeDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $assignee;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(string $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getEnglish(): ?string
    {
        return $this->english;
    }

    public function setEnglish(string $english): self
    {
        $this->english = $english;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function setBonus(?string $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getResponsibilities(): ?string
    {
        return $this->responsibilities;
    }

    public function setResponsibilities(string $responsibilities): self
    {
        $this->responsibilities = $responsibilities;

        return $this;
    }

    public function getRequirements(): ?string
    {
        return $this->requirements;
    }

    public function setRequirements(string $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getPlus(): ?string
    {
        return $this->plus;
    }

    public function setPlus(?string $plus): self
    {
        $this->plus = $plus;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getReasonDenied(): ?string
    {
        return $this->reasonDenied;
    }

    public function setReasonDenied(?string $reasonDenied): self
    {
        $this->reasonDenied = $reasonDenied;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(?bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

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


    public function getApproveDate(): ?DateTimeImmutable
    {
        return $this->approveDate;
    }

    public function setApproveDate(?DateTimeImmutable $approveDate): self
    {
        $this->approveDate = $approveDate;

        return $this;
    }

    public function getVacancyViewerUser(): ?VacancyViewerUser
    {
        return $this->vacancyViewerUser;
    }

    public function setVacancyViewerUser(?VacancyViewerUser $vacancyViewerUser): self
    {
        $this->vacancyViewerUser = $vacancyViewerUser;

        return $this;
    }

    public function getAssigneeDate(): ?DateTimeImmutable
    {
        return $this->assigneeDate;
    }

    public function setAssigneeDate(?DateTimeImmutable $assigneeDate): self
    {
        $this->assigneeDate = $assigneeDate;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }


}
