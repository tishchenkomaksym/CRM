<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QaRequiredSkillTestMarkRepository")
 */
class QaRequiredSkillTestMark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevel", inversedBy="qaRequiredSkillTestMarks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qaLevel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QaSkillTest")
     * @ORM\JoinColumn(nullable=false)
     */
    private $test;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $requiredPoints;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQaLevel(): ?PhpDeveloperLevel
    {
        return $this->qaLevel;
    }

    public function setQaLevel(?PhpDeveloperLevel $qaLevel): self
    {
        $this->qaLevel = $qaLevel;

        return $this;
    }

    public function getTest(): ?QaSkillTest
    {
        return $this->test;
    }

    public function setTest(?QaSkillTest $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getRequiredPoints(): ?int
    {
        return $this->requiredPoints;
    }

    public function setRequiredPoints(?int $requiredPoints): self
    {
        $this->requiredPoints = $requiredPoints;

        return $this;
    }
}
