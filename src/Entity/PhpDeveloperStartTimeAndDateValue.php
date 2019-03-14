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
    private $reateDate;

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

    public function getReateDate(): ?\DateTimeImmutable
    {
        return $this->reateDate;
    }

    public function setReateDate(\DateTimeImmutable $reateDate): self
    {
        $this->reateDate = $reateDate;

        return $this;
    }
}
