<?php

namespace App\Entity;

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
}
