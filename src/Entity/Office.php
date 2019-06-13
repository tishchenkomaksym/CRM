<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfficeRepository")
 */
class Office
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
     * @ORM\OneToMany(targetEntity="App\Entity\Department", mappedBy="office")
     */
    private $departments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offices")
     */
    private $topManager;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConfRoom", mappedBy="office")
     */
    private $confRooms;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->confRooms = new ArrayCollection();
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

    /**
     * @return Collection|Department[]
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments[] = $department;
            $department->setOffice($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->contains($department)) {
            $this->departments->removeElement($department);
            // set the owning side to null (unless already changed)
            if ($department->getOffice() === $this) {
                $department->setOffice(null);
            }
        }

        return $this;
    }

    public function getTopManager(): ?User
    {
        return $this->topManager;
    }

    public function setTopManager(?User $topManager): self
    {
        $this->topManager = $topManager;

        return $this;
    }

    /**
     * @return Collection|ConfRoom[]
     */
    public function getConfRooms(): Collection
    {
        return $this->confRooms;
    }

    public function addConfRoom(ConfRoom $confRoom): self
    {
        if (!$this->confRooms->contains($confRoom)) {
            $this->confRooms[] = $confRoom;
            $confRoom->setOffice($this);
        }

        return $this;
    }

    public function removeConfRoom(ConfRoom $confRoom): self
    {
        if ($this->confRooms->contains($confRoom)) {
            $this->confRooms->removeElement($confRoom);
            // set the owning side to null (unless already changed)
            if ($confRoom->getOffice() === $this) {
                $confRoom->setOffice(null);
            }
        }

        return $this;
    }
}
