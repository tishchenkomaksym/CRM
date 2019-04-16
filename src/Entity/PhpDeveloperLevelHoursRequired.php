<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperLevelHoursRequiredRepository")
 */
class PhpDeveloperLevelHoursRequired
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PhpDeveloperLevel", inversedBy="phpDeveloperLevelHoursRequired", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $phpDeveloperLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $effectiveTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $effectiveProjectTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhpDeveloperLevel(): ?PhpDeveloperLevel
    {
        return $this->phpDeveloperLevel;
    }

    public function setPhpDeveloperLevel(PhpDeveloperLevel $phpDeveloperLevel): self
    {
        $this->phpDeveloperLevel = $phpDeveloperLevel;

        return $this;
    }

    public function getEffectiveTime(): ?int
    {
        return $this->effectiveTime;
    }

    public function setEffectiveTime(int $effectiveTime): self
    {
        $this->effectiveTime = $effectiveTime;

        return $this;
    }

    public function getEffectiveProjectTime(): ?int
    {
        return $this->effectiveProjectTime;
    }

    public function setEffectiveProjectTime(int $effectiveProjectTime): self
    {
        $this->effectiveProjectTime = $effectiveProjectTime;

        return $this;
    }
}
