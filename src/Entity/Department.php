<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 */
class Department
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Office", inversedBy="departments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $office;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="department")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DepartmentTeamSdtViewRules", mappedBy="department", orphanRemoval=true)
     */
    private $departmentTeamSdtViewRules;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->departmentTeamSdtViewRules = new ArrayCollection();
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

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    public function setOffice(?Office $office): self
    {
        $this->office = $office;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setDepartment($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getDepartment() === $this) {
                $team->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DepartmentTeamSdtViewRules[]
     */
    public function getDepartmentTeamSdtViewRules(): Collection
    {
        return $this->departmentTeamSdtViewRules;
    }

    public function addDepartmentTeamSdtViewRule(DepartmentTeamSdtViewRules $departmentTeamSdtViewRule): self
    {
        if (!$this->departmentTeamSdtViewRules->contains($departmentTeamSdtViewRule)) {
            $this->departmentTeamSdtViewRules[] = $departmentTeamSdtViewRule;
            $departmentTeamSdtViewRule->setDepartment($this);
        }

        return $this;
    }

    public function removeDepartmentTeamSdtViewRule(DepartmentTeamSdtViewRules $departmentTeamSdtViewRule): self
    {
        if ($this->departmentTeamSdtViewRules->contains($departmentTeamSdtViewRule)) {
            $this->departmentTeamSdtViewRules->removeElement($departmentTeamSdtViewRule);
            // set the owning side to null (unless already changed)
            if ($departmentTeamSdtViewRule->getDepartment() === $this) {
                $departmentTeamSdtViewRule->setDepartment(null);
            }
        }

        return $this;
    }

}
