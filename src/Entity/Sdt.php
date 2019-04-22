<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SdtRepository")
 */
class Sdt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sdt")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $acting;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $reportDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $atOwnExpense;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateDate(): ?DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(DateTimeInterface $create_date): self
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getActing(): ?string
    {
        return $this->acting;
    }

    public function setActing(string $acting): self
    {
        $this->acting = $acting;

        return $this;
    }

    public function getReportDate(): ?DateTimeImmutable
    {
        return $this->reportDate;
    }

    public function setReportDate(DateTimeImmutable $reportDate): self
    {
        $this->reportDate = $reportDate;

        return $this;
    }

    public function getAtOwnExpense(): ?bool
    {
        return $this->atOwnExpense;
    }

    public function setAtOwnExpense(bool $atOwnExpense): self
    {
        $this->atOwnExpense = $atOwnExpense;

        return $this;
    }
}
