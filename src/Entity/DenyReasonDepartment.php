<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DenyReasonDepartmentRepository")
 */
class DenyReasonDepartment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DenyChoiceDepartment", inversedBy="denyReasonDepartments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $denyChoiceDepartment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateManagerDeny", inversedBy="denyReasonDepartments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidateManagerDeny;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenyChoiceDepartment(): ?DenyChoiceDepartment
    {
        return $this->denyChoiceDepartment;
    }

    public function setDenyChoiceDepartment(?DenyChoiceDepartment $denyChoiceDepartment): self
    {
        $this->denyChoiceDepartment = $denyChoiceDepartment;

        return $this;
    }

    public function getCandidateManagerDeny(): ?CandidateManagerDeny
    {
        return $this->candidateManagerDeny;
    }

    public function setCandidateManagerDeny(?CandidateManagerDeny $candidateManagerDeny): self
    {
        $this->candidateManagerDeny = $candidateManagerDeny;

        return $this;
    }
}
