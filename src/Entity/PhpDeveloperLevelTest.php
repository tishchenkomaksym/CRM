<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperLevelTestRepository")
 */
class PhpDeveloperLevelTest
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevel", inversedBy="phpDeveloperLevelTests")
     */
    private $phpDeveloperLevel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperLevelTestPassed", mappedBy="phpDeveloperLevelTest", orphanRemoval=true)
     */
    private $phpDeveloperLevelTestPasseds;

    /**
     * @ORM\Column(type="text")
     */
    private $information;

    public function __construct()
    {
        $this->phpDeveloperLevelTestPasseds = new ArrayCollection();
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPhpDeveloperLevel(): ?PhpDeveloperLevel
    {
        return $this->phpDeveloperLevel;
    }

    public function setPhpDeveloperLevel(?PhpDeveloperLevel $phpDeveloperLevel): self
    {
        $this->phpDeveloperLevel = $phpDeveloperLevel;

        return $this;
    }

    /**
     * @return Collection|PhpDeveloperLevelTestPassed[]
     */
    public function getPhpDeveloperLevelTestPasseds(): Collection
    {
        return $this->phpDeveloperLevelTestPasseds;
    }

    public function addPhpDeveloperLevelTestPassed(PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed): self
    {
        if (!$this->phpDeveloperLevelTestPasseds->contains($phpDeveloperLevelTestPassed)) {
            $this->phpDeveloperLevelTestPasseds[] = $phpDeveloperLevelTestPassed;
            $phpDeveloperLevelTestPassed->setPhpDeveloperLevelTest($this);
        }

        return $this;
    }

    public function removePhpDeveloperLevelTestPassed(PhpDeveloperLevelTestPassed $phpDeveloperLevelTestPassed): self
    {
        if ($this->phpDeveloperLevelTestPasseds->contains($phpDeveloperLevelTestPassed)) {
            $this->phpDeveloperLevelTestPasseds->removeElement($phpDeveloperLevelTestPassed);
            // set the owning side to null (unless already changed)
            if ($phpDeveloperLevelTestPassed->getPhpDeveloperLevelTest() === $this) {
                $phpDeveloperLevelTestPassed->setPhpDeveloperLevelTest(null);
            }
        }

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }
}
