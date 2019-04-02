<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
     */
    private $office;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $requestReason;

    /**
     * @ORM\Column(type="text")
     */
    private $requirement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffice(): ?string
    {
        return $this->office;
    }

    public function setOffice(string $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRequestReason(): ?string
    {
        return $this->requestReason;
    }

    public function setRequestReason(string $requestReason): self
    {
        $this->requestReason = $requestReason;

        return $this;
    }

    public function getRequirement(): ?string
    {
        return $this->requirement;
    }

    public function setRequirement(string $requirement): self
    {
        $this->requirement = $requirement;

        return $this;
    }
}
