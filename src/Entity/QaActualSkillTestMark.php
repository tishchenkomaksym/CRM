<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QaActualSkillTestMarkRepository")
 */
class QaActualSkillTestMark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="qaActualSkillTestMarks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QaSkillTest")
     */
    private $qaSkillTest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $actualPoints;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQaSkillTest(): ?QaSkillTest
    {
        return $this->qaSkillTest;
    }

    public function setQaSkillTest(?QaSkillTest $qaSkillTest): self
    {
        $this->qaSkillTest = $qaSkillTest;

        return $this;
    }

    public function getActualPoints(): ?int
    {
        return $this->actualPoints;
    }

    public function setActualPoints(?int $actualPoints): self
    {
        $this->actualPoints = $actualPoints;

        return $this;
    }
}
