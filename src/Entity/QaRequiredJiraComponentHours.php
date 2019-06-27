<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QaRequiredJiraComponentHoursRepository")
 */
class QaRequiredJiraComponentHours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevel", inversedBy="qaRequiredJiraComponentHours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qaLevel;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $requiredHours;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QaJiraComponent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jiraComponent;

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

    public function getRequiredHours(): ?float
    {
        return $this->requiredHours;
    }

    public function setRequiredHours(?float $requiredHours): self
    {
        $this->requiredHours = $requiredHours;

        return $this;
    }

    public function getJiraComponent(): ?QaJiraComponent
    {
        return $this->jiraComponent;
    }

    public function setJiraComponent(?QaJiraComponent $jiraComponent): self
    {
        $this->jiraComponent = $jiraComponent;

        return $this;
    }
}
