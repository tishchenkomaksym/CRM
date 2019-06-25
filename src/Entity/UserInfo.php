<?php

namespace App\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserInfoRepository")
 */
class UserInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subTeam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $personalEmail;

    /**
     * @var string $type
     *
     * @ORM\Column(name="sex", nullable=true,  type="string", length=255, columnDefinition="ENUM('male', 'female')")
     */
    private $sex;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDay;

    /**
     * @var string $type
     *
     * @ORM\Column(name="maritalStatus", nullable=true, type="string", length=255, columnDefinition="ENUM('single', 'married', 'divorced')")
     */
    private $maritalStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $children;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User",  cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $salary;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSubTeam(): ?string
    {
        return $this->subTeam;
    }

    public function setSubTeam(?string $subTeam): self
    {
        $this->subTeam = $subTeam;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }

    public function getPersonalEmail(): ?string
    {
        return $this->personalEmail;
    }

    public function setPersonalEmail(?string $personalEmail): self
    {
        $this->personalEmail = $personalEmail;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthDay(): ?Datetime
    {
        return $this->birthDay;
    }

    public function setBirthDay(Datetime $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(string $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    public function getChildren(): ?string
    {
        return $this->children;
    }

    public function setChildren(?string $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
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

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(?int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->photo,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->photo,
            ) = unserialize($serialized, array('allowed_classes' => false));
    }
}
