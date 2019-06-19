<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DenyReasonCandidateRepository")
 */
class DenyReasonCandidate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DenyChoiceCandidate", inversedBy="denyReasonCandidates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $denyChoiceCandidate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateOfferDeny", inversedBy="denyReasonCandidates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidateOfferDeny;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenyChoiceCandidate(): ?DenyChoiceCandidate
    {
        return $this->denyChoiceCandidate;
    }

    public function setDenyChoiceCandidate(?DenyChoiceCandidate $denyChoiceCandidate): self
    {
        $this->denyChoiceCandidate = $denyChoiceCandidate;

        return $this;
    }

    public function getCandidateOfferDeny(): ?CandidateOfferDeny
    {
        return $this->candidateOfferDeny;
    }

    public function setCandidateOfferDeny(?CandidateOfferDeny $candidateOfferDeny): self
    {
        $this->candidateOfferDeny = $candidateOfferDeny;

        return $this;
    }
}
