<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sdt", mappedBy="user", cascade={"persist", "remove"})
     */
    private $sdt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MonthlySdt", mappedBy="user")
     */
    private $monthlySdts;

    public function __construct()
    {
        $this->monthlySdts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getSdt(): ?Sdt
    {
        return $this->sdt;
    }

    public function setSdt(Sdt $sdt): self
    {
        $this->sdt = $sdt;

        // set the owning side of the relation if necessary
        if ($this !== $sdt->getUser()) {
            $sdt->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|MonthlySdt[]
     */
    public function getMonthlySdts(): Collection
    {
        return $this->monthlySdts;
    }

    public function addMonthlySdt(MonthlySdt $monthlySdt): self
    {
        if (!$this->monthlySdts->contains($monthlySdt)) {
            $this->monthlySdts[] = $monthlySdt;
            $monthlySdt->setUserId($this);
        }

        return $this;
    }

    public function removeMonthlySdt(MonthlySdt $monthlySdt): self
    {
        if ($this->monthlySdts->contains($monthlySdt)) {
            $this->monthlySdts->removeElement($monthlySdt);
            // set the owning side to null (unless already changed)
            if ($monthlySdt->getUserId() === $this) {
                $monthlySdt->setUserId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
