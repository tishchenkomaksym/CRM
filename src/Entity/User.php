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

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPhpDeveloperLevelRelation", mappedBy="user", cascade={"persist",
     *     "remove"})
     */
    private $phpDeveloperLevelRelation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperLevelTestPassed", mappedBy="user", orphanRemoval=true)
     */
    private $phpDeveloperLevelTestsPassed;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperManagerRelation", mappedBy="phpDeveloper", orphanRemoval=true)
     */
    private $phpDeveloperManagerRelations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperManagerRelation", mappedBy="manager", orphanRemoval=true)
     */
    private $phpManagerDeveloperRelations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhpDeveloperRiseRequest", mappedBy="phpDeveloper", orphanRemoval=true)
     */
    private $phpDeveloperRiseRequests;

    public function __construct()
    {
        $this->monthlySdts = new ArrayCollection();
        $this->phpDeveloperLevelTestsPassed = new ArrayCollection();
        $this->phpDeveloperManagerRelations = new ArrayCollection();
        $this->phpManagerDeveloperRelations = new ArrayCollection();
        $this->phpDeveloperRiseRequests = new ArrayCollection();
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

    public function getPhpDeveloperLevelRelation(): ?UserPhpDeveloperLevelRelation
    {
        return $this->phpDeveloperLevelRelation;
    }

    public function setPhpDeveloperLevelRelation(UserPhpDeveloperLevelRelation $phpDeveloperLevelRelation): self
    {
        $this->phpDeveloperLevelRelation = $phpDeveloperLevelRelation;

        // set the owning side of the relation if necessary
        if ($this !== $phpDeveloperLevelRelation->getUser()) {
            $phpDeveloperLevelRelation->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|PhpDeveloperLevelTestPassed[]
     */
    public function getPhpDeveloperLevelTestsPassed(): Collection
    {
        return $this->phpDeveloperLevelTestsPassed;
    }

    public function addPhpDeveloperLevelTestsPassed(PhpDeveloperLevelTestPassed $phpDeveloperLevelTestsPassed): self
    {
        if (!$this->phpDeveloperLevelTestsPassed->contains($phpDeveloperLevelTestsPassed)) {
            $this->phpDeveloperLevelTestsPassed[] = $phpDeveloperLevelTestsPassed;
            $phpDeveloperLevelTestsPassed->setUser($this);
        }

        return $this;
    }

    public function removePhpDeveloperLevelTestsPassed(PhpDeveloperLevelTestPassed $phpDeveloperLevelTestsPassed): self
    {
        if ($this->phpDeveloperLevelTestsPassed->contains($phpDeveloperLevelTestsPassed)) {
            $this->phpDeveloperLevelTestsPassed->removeElement($phpDeveloperLevelTestsPassed);
            // set the owning side to null (unless already changed)
            if ($phpDeveloperLevelTestsPassed->getUser() === $this) {
                $phpDeveloperLevelTestsPassed->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PhpDeveloperManagerRelation[]
     */
    public function getPhpDeveloperManagerRelations(): Collection
    {
        return $this->phpDeveloperManagerRelations;
    }

    public function addPhpDeveloperManagerRelation(PhpDeveloperManagerRelation $phpDeveloperManagerRelation): self
    {
        if (!$this->phpDeveloperManagerRelations->contains($phpDeveloperManagerRelation)) {
            $this->phpDeveloperManagerRelations[] = $phpDeveloperManagerRelation;
            $phpDeveloperManagerRelation->setPhpDeveloper($this);
        }

        return $this;
    }

    public function removePhpDeveloperManagerRelation(PhpDeveloperManagerRelation $phpDeveloperManagerRelation): self
    {
        if ($this->phpDeveloperManagerRelations->contains($phpDeveloperManagerRelation)) {
            $this->phpDeveloperManagerRelations->removeElement($phpDeveloperManagerRelation);
            // set the owning side to null (unless already changed)
            if ($phpDeveloperManagerRelation->getPhpDeveloper() === $this) {
                $phpDeveloperManagerRelation->setPhpDeveloper(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PhpDeveloperManagerRelation[]
     */
    public function getPhpManagerDeveloperRelations(): Collection
    {
        return $this->phpManagerDeveloperRelations;
    }

    public function addPhpManagerDeveloperRelation(PhpDeveloperManagerRelation $phpManagerDeveloperRelation): self
    {
        if (!$this->phpManagerDeveloperRelations->contains($phpManagerDeveloperRelation)) {
            $this->phpManagerDeveloperRelations[] = $phpManagerDeveloperRelation;
            $phpManagerDeveloperRelation->setManager($this);
        }

        return $this;
    }

    public function removePhpManagerDeveloperRelation(PhpDeveloperManagerRelation $phpManagerDeveloperRelation): self
    {
        if ($this->phpManagerDeveloperRelations->contains($phpManagerDeveloperRelation)) {
            $this->phpManagerDeveloperRelations->removeElement($phpManagerDeveloperRelation);
            // set the owning side to null (unless already changed)
            if ($phpManagerDeveloperRelation->getManager() === $this) {
                $phpManagerDeveloperRelation->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PhpDeveloperRiseRequest[]
     */
    public function getPhpDeveloperRiseRequests(): Collection
    {
        return $this->phpDeveloperRiseRequests;
    }

    public function addPhpDeveloperRiseRequest(PhpDeveloperRiseRequest $phpDeveloperRiseRequest): self
    {
        if (!$this->phpDeveloperRiseRequests->contains($phpDeveloperRiseRequest)) {
            $this->phpDeveloperRiseRequests[] = $phpDeveloperRiseRequest;
            $phpDeveloperRiseRequest->setPhpDeveloper($this);
        }

        return $this;
    }

    public function removePhpDeveloperRiseRequest(PhpDeveloperRiseRequest $phpDeveloperRiseRequest): self
    {
        if ($this->phpDeveloperRiseRequests->contains($phpDeveloperRiseRequest)) {
            $this->phpDeveloperRiseRequests->removeElement($phpDeveloperRiseRequest);
            // set the owning side to null (unless already changed)
            if ($phpDeveloperRiseRequest->getPhpDeveloper() === $this) {
                $phpDeveloperRiseRequest->setPhpDeveloper(null);
            }
        }

        return $this;
    }
}
