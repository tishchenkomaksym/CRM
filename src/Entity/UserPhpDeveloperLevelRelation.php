<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPhpDeveloperLevelRelationRepository")
 */
class UserPhpDeveloperLevelRelation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="phpDeveloperLevelRelation", cascade={"persist",
     *     "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevel", inversedBy="phpDeveloperRelations",
     *     cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $phpDeveloperLevel;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $createDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPhpDeveloperLevel(): ?PhpDeveloperLevel
    {
        return $this->phpDeveloperLevel;
    }

    public function setPhpDeveloperLevel(PhpDeveloperLevel $phpDeveloperLevel): self
    {
        $this->phpDeveloperLevel = $phpDeveloperLevel;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeImmutable
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeImmutable $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }
}
