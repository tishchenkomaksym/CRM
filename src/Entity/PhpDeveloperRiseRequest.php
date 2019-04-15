<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperRiseRequestRepository")
 */
class PhpDeveloperRiseRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="phpDeveloperRiseRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phpDeveloper;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approved;

    /**
     * @ORM\Column(type="date")
     */
    private $createdDate;

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

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }
}
