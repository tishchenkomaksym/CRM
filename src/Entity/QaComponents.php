<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QaComponentsRepository")
 */
class QaComponents
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
     * @ORM\Column(type="string", length=1000)
     */
    private $childComponents;

    /**
     * @ORM\Column(type="integer")
     */
    private $firstLvlHours;

    /**
     * @ORM\Column(type="integer")
     */
    private $secondLvlHours;

    /**
     * @ORM\Column(type="integer")
     */
    private $thirdLvlHours;

    /**
     * @ORM\Column(type="integer")
     */
    private $fourLvlHours;

    /**
     * @ORM\Column(type="integer")
     */
    private $fiveLvlHours;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

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

    public function getChildComponents(): ?string
    {
        return $this->childComponents;
    }

    public function setChildComponents(string $childComponents): self
    {
        $this->childComponents = $childComponents;

        return $this;
    }

    public function getFirstLvlHours(): ?int
    {
        return $this->firstLvlHours;
    }

    public function setFirstLvlHours(int $firstLvlHours): self
    {
        $this->firstLvlHours = $firstLvlHours;

        return $this;
    }

    public function getSecondLvlHours(): ?int
    {
        return $this->secondLvlHours;
    }

    public function setSecondLvlHours(int $secondLvlHours): self
    {
        $this->secondLvlHours = $secondLvlHours;

        return $this;
    }

    public function getThirdLvlHours(): ?int
    {
        return $this->thirdLvlHours;
    }

    public function setThirdLvlHours(int $thirdLvlHours): self
    {
        $this->thirdLvlHours = $thirdLvlHours;

        return $this;
    }

    public function getFourLvlHours(): ?int
    {
        return $this->fourLvlHours;
    }

    public function setFourLvlHours(int $fourLvlHours): self
    {
        $this->fourLvlHours = $fourLvlHours;

        return $this;
    }

    public function getFiveLvlHours(): ?int
    {
        return $this->fiveLvlHours;
    }

    public function setFiveLvlHours(int $fiveLvlHours): self
    {
        $this->fiveLvlHours = $fiveLvlHours;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
