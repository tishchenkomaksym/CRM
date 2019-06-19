<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="teams")
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="team")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CandidateManagerApproval", mappedBy="team")
     */
    private $candidateManagerApprovals;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->candidateManagerApprovals = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function gettId(): ?int
    {
        return $this->name;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getTeam() === $this) {
                $user->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CandidateManagerApproval[]
     */
    public function getCandidateManagerApprovals(): Collection
    {
        return $this->candidateManagerApprovals;
    }

    public function addCandidateManagerApproval(CandidateManagerApproval $candidateManagerApproval): self
    {
        if (!$this->candidateManagerApprovals->contains($candidateManagerApproval)) {
            $this->candidateManagerApprovals[] = $candidateManagerApproval;
            $candidateManagerApproval->setTeam($this);
        }

        return $this;
    }

    public function removeCandidateManagerApproval(CandidateManagerApproval $candidateManagerApproval): self
    {
        if ($this->candidateManagerApprovals->contains($candidateManagerApproval)) {
            $this->candidateManagerApprovals->removeElement($candidateManagerApproval);
            // set the owning side to null (unless already changed)
            if ($candidateManagerApproval->getTeam() === $this) {
                $candidateManagerApproval->setTeam(null);
            }
        }

        return $this;
    }


}
