<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpDeveloperLevelTestRepository")
 */
class PhpDeveloperLevelTest
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhpDeveloperLevel", inversedBy="phpDeveloperLevelTests")
     */
    private $phpDeveloperLevel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPhpDeveloperLevel(): ?PhpDeveloperLevel
    {
        return $this->phpDeveloperLevel;
    }

    public function setPhpDeveloperLevel(?PhpDeveloperLevel $phpDeveloperLevel): self
    {
        $this->phpDeveloperLevel = $phpDeveloperLevel;

        return $this;
    }
}
