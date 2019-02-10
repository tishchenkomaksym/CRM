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
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperLevelTest", mappedBy="phpDeveloperLevel")
     */
    private $phpDeveloperLevelTests;

    public function __construct()
    {
        $this->phpDeveloperLevelTests = new ArrayCollection();
        $this->phpDeveloperRelations = new ArrayCollection();
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
}
