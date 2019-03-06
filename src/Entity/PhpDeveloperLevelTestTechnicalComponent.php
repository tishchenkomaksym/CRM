<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperLevelTestTechnicalComponentRepository")
 */
class PhpDeveloperLevelTestTechnicalComponent
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jiraName;

    /**
     * @ORM\Column(type="integer")
     */
    private $requiredHours;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevelTest", inversedBy="phpDeveloperLevelTestTechnicalComponents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phpDeveloperLevel;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getJiraName(): ?string
    {
        return $this->jiraName;
    }

    public function setJiraName(string $jiraName): self
    {
        $this->jiraName = $jiraName;

        return $this;
    }

    public function getRequiredHours(): ?int
    {
        return $this->requiredHours;
    }

    public function setRequiredHours(int $requiredHours): self
    {
        $this->requiredHours = $requiredHours;

        return $this;
    }

    public function getPhpDeveloperLevel(): ?PhpDeveloperLevelTest
    {
        return $this->phpDeveloperLevel;
    }

    public function setPhpDeveloperLevel(?PhpDeveloperLevelTest $phpDeveloperLevel): self
    {
        $this->phpDeveloperLevel = $phpDeveloperLevel;

        return $this;
    }
}
