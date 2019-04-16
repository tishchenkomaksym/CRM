<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperLevelTestPassedRepository")
 */
class PhpDeveloperLevelTestPassed
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="phpDeveloperLevelTestsPassed")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevelTest", inversedBy="phpDeveloperLevelTestPasseds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phpDeveloperLevelTest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPhpDeveloperLevelTest(): ?PhpDeveloperLevelTest
    {
        return $this->phpDeveloperLevelTest;
    }

    public function setPhpDeveloperLevelTest(?PhpDeveloperLevelTest $phpDeveloperLevelTest): self
    {
        $this->phpDeveloperLevelTest = $phpDeveloperLevelTest;

        return $this;
    }
}
