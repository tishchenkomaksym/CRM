<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperLevelRepository")
 */
class PhpDeveloperLevel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPhpDeveloperLevelRelation", mappedBy="phpDeveloperLevel",)
     */
    private $phpDeveloperRelations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperLevelTest", mappedBy="phpDeveloperLevel", cascade={"persist", "remove"})
     */
    private $phpDeveloperLevelTests;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PhpDeveloperLevel", cascade={"persist", "remove"})
     */
    private $nextLevel;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PhpDeveloperLevelHoursRequired", mappedBy="phpDeveloperLevel", cascade={"persist", "remove"})
     */
    private $phpDeveloperLevelHoursRequired;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QaRequiredSkillTestMark", mappedBy="qaLevel")
     */
    private $qaRequiredSkillTestMarks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QaRequiredJiraComponentHours", mappedBy="qaLevel", orphanRemoval=true)
     */
    private $qaRequiredJiraComponentHours;

    public function __construct()
    {
        $this->phpDeveloperLevelTests = new ArrayCollection();
        $this->phpDeveloperRelations = new ArrayCollection();
        $this->test = new ArrayCollection();
        $this->qaRequiredSkillTestMarks = new ArrayCollection();
        $this->qaRequiredJiraComponentHours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPhpDeveloperRelations(): Collection
    {
        return $this->phpDeveloperRelations;
    }

    public function setPhpDeveloperRelations(UserPhpDeveloperLevelRelation $phpDeveloperRelations): self
    {
        if (!$this->phpDeveloperRelations->contains($phpDeveloperRelations)) {
            $this->phpDeveloperRelations[] = $phpDeveloperRelations;
            $phpDeveloperRelations->setPhpDeveloperLevel($this);
        }

        return $this;
    }

    /**
     * @return Collection|PhpDeveloperLevelTest[]
     */
    public function getPhpDeveloperLevelTests(): Collection
    {
        return $this->phpDeveloperLevelTests;
    }

    public function addPhpDeveloperLevelTest(PhpDeveloperLevelTest $phpDeveloperLevelTest): self
    {
        if (!$this->phpDeveloperLevelTests->contains($phpDeveloperLevelTest)) {
            $this->phpDeveloperLevelTests[] = $phpDeveloperLevelTest;
            $phpDeveloperLevelTest->setPhpDeveloperLevel($this);
        }

        return $this;
    }

    public function removePhpDeveloperLevelTest(PhpDeveloperLevelTest $phpDeveloperLevelTest): self
    {
        if ($this->phpDeveloperLevelTests->contains($phpDeveloperLevelTest)) {
            $this->phpDeveloperLevelTests->removeElement($phpDeveloperLevelTest);
            // set the owning side to null (unless already changed)
            if ($phpDeveloperLevelTest->getPhpDeveloperLevel() === $this) {
                $phpDeveloperLevelTest->setPhpDeveloperLevel(null);
            }
        }

        return $this;
    }

    public function getNextLevel(): ?self
    {
        return $this->nextLevel;
    }

    public function setNextLevel(?self $nextLevel): self
    {
        $this->nextLevel = $nextLevel;

        return $this;
    }

    public function getPhpDeveloperLevelHoursRequired(): ?PhpDeveloperLevelHoursRequired
    {
        return $this->phpDeveloperLevelHoursRequired;
    }

    public function setPhpDeveloperLevelHoursRequired(PhpDeveloperLevelHoursRequired $phpDeveloperLevelHoursRequired): self
    {
        $this->phpDeveloperLevelHoursRequired = $phpDeveloperLevelHoursRequired;

        // set the owning side of the relation if necessary
        if ($this !== $phpDeveloperLevelHoursRequired->getPhpDeveloperLevel()) {
            $phpDeveloperLevelHoursRequired->setPhpDeveloperLevel($this);
        }

        return $this;
    }

    /**
     * @return Collection|QaRequiredSkillTestMark[]
     */
    public function getQaRequiredSkillTestMarks(): Collection
    {
        return $this->qaRequiredSkillTestMarks;
    }

    public function addQaRequiredSkillTestMark(QaRequiredSkillTestMark $qaRequiredSkillTestMark): self
    {
        if (!$this->qaRequiredSkillTestMarks->contains($qaRequiredSkillTestMark)) {
            $this->qaRequiredSkillTestMarks[] = $qaRequiredSkillTestMark;
            $qaRequiredSkillTestMark->setQaLevel($this);
        }

        return $this;
    }

    public function removeQaRequiredSkillTestMark(QaRequiredSkillTestMark $qaRequiredSkillTestMark): self
    {
        if ($this->qaRequiredSkillTestMarks->contains($qaRequiredSkillTestMark)) {
            $this->qaRequiredSkillTestMarks->removeElement($qaRequiredSkillTestMark);
            // set the owning side to null (unless already changed)
            if ($qaRequiredSkillTestMark->getQaLevel() === $this) {
                $qaRequiredSkillTestMark->setQaLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QaRequiredJiraComponentHours[]
     */
    public function getQaRequiredJiraComponentHours(): Collection
    {
        return $this->qaRequiredJiraComponentHours;
    }

    public function addQaRequiredJiraComponentHour(QaRequiredJiraComponentHours $qaRequiredJiraComponentHour): self
    {
        if (!$this->qaRequiredJiraComponentHours->contains($qaRequiredJiraComponentHour)) {
            $this->qaRequiredJiraComponentHours[] = $qaRequiredJiraComponentHour;
            $qaRequiredJiraComponentHour->setQaLevel($this);
        }

        return $this;
    }

    public function removeQaRequiredJiraComponentHour(QaRequiredJiraComponentHours $qaRequiredJiraComponentHour): self
    {
        if ($this->qaRequiredJiraComponentHours->contains($qaRequiredJiraComponentHour)) {
            $this->qaRequiredJiraComponentHours->removeElement($qaRequiredJiraComponentHour);
            // set the owning side to null (unless already changed)
            if ($qaRequiredJiraComponentHour->getQaLevel() === $this) {
                $qaRequiredJiraComponentHour->setQaLevel(null);
            }
        }

        return $this;
    }

}
