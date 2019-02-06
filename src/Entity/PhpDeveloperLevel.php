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
     * @ORM\OneToOne(targetEntity="App\Entity\UserPhpDeveloperLevelRelation", mappedBy="phpDeveloperLevel",
     *     cascade={"persist", "remove"})
     */
    private $phpDeveloperRelation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperLevelTest", mappedBy="phpDeveloperLevel")
     */
    private $phpDeveloperLevelTests;

    public function __construct()
    {
        $this->phpDeveloperLevelTests = new ArrayCollection();
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

    public function getPhpDeveloperRelation(): ?UserPhpDeveloperLevelRelation
    {
        return $this->phpDeveloperRelation;
    }

    public function setPhpDeveloperRelation(UserPhpDeveloperLevelRelation $phpDeveloperRelation): self
    {
        $this->phpDeveloperRelation = $phpDeveloperRelation;

        // set the owning side of the relation if necessary
        if ($this !== $phpDeveloperRelation->getPhpDeveloperLevel()) {
            $phpDeveloperRelation->setPhpDeveloperLevel($this);
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
