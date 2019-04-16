<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperStartTimeAndDateValueRepository")
 */
class PhpDeveloperStartTimeAndDateValue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $effectiveTime;

    /**
     * @ORM\Column(type="float")
     */
    private $effectiveProjectTime;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createDate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="phpDeveloperStartTimeAndDateValue", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEffectiveTime(): ?float
    {
        return $this->effectiveTime;
    }

    public function setEffectiveTime(float $effectiveTime): self
    {
        $this->effectiveTime = $effectiveTime;

        return $this;
    }

    public function getEffectiveProjectTime(): ?float
    {
        return $this->effectiveProjectTime;
    }

    public function setEffectiveProjectTime(float $effectiveProjectTime): self
    {
        $this->effectiveProjectTime = $effectiveProjectTime;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeImmutable
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeImmutable $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
