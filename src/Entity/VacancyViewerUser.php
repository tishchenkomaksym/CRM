<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacancyViewerUserRepository")
 */
class VacancyViewerUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="vacancyViewerUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $permissionUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vacancy", mappedBy="vacancyViewerUser")
     */
    private $vacancies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentViewer", mappedBy="vacancyViewerUser")
     */
    private $commentViewers;


    public function __construct()
    {
        $this->vacancies = new ArrayCollection();
        $this->commentViewers = new ArrayCollection();
    }

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

    public function getPermissionUser(): ?User
    {
        return $this->permissionUser;
    }

    public function setPermissionUser(User $permissionUser): self
    {
        $this->permissionUser = $permissionUser;

        return $this;
    }

    /**
     * @return Collection|Vacancy[]
     */
    public function getVacancies(): Collection
    {
        return $this->vacancies;
    }

    public function addVacancy(Vacancy $vacancy): self
    {
        if (!$this->vacancies->contains($vacancy)) {
            $this->vacancies[] = $vacancy;
            $vacancy->setVacancyViewerUser($this);
        }

        return $this;
    }

    public function removeVacancy(Vacancy $vacancy): self
    {
        if ($this->vacancies->contains($vacancy)) {
            $this->vacancies->removeElement($vacancy);
            // set the owning side to null (unless already changed)
            if ($vacancy->getVacancyViewerUser() === $this) {
                $vacancy->setVacancyViewerUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentViewer[]
     */
    public function getCommentViewers(): Collection
    {
        return $this->commentViewers;
    }

    public function addCommentViewer(CommentViewer $commentViewer): self
    {
        if (!$this->commentViewers->contains($commentViewer)) {
            $this->commentViewers[] = $commentViewer;
            $commentViewer->setVacancyViewerUser($this);
        }

        return $this;
    }

    public function removeCommentViewer(CommentViewer $commentViewer): self
    {
        if ($this->commentViewers->contains($commentViewer)) {
            $this->commentViewers->removeElement($commentViewer);
            // set the owning side to null (unless already changed)
            if ($commentViewer->getVacancyViewerUser() === $this) {
                $commentViewer->setVacancyViewerUser(null);
            }
        }

        return $this;
    }
}
