<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QaUserManagerRelationRepository")
 */
class QaUserManagerRelation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qaUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qaManager;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQaUser(): ?User
    {
        return $this->qaUser;
    }

    public function setQaUser(?User $qaUser): self
    {
        $this->qaUser = $qaUser;

        return $this;
    }

    public function getQaManager(): ?User
    {
        return $this->qaManager;
    }

    public function setQaManager(?User $qaManager): self
    {
        $this->qaManager = $qaManager;

        return $this;
    }
}
