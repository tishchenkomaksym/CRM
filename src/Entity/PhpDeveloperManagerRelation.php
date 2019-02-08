<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperManagerRelationRepository")
 */
class PhpDeveloperManagerRelation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="phpDeveloperManagerRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phpDeveloper;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="phpManagerDeveloperRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhpDeveloper(): ?User
    {
        return $this->phpDeveloper;
    }

    public function setPhpDeveloper(?User $phpDeveloper): self
    {
        $this->phpDeveloper = $phpDeveloper;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }
}
