<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentViewerRepository")
 */
class CommentViewer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VacancyViewerUser", inversedBy="commentViewers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacancyViewerUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateLink", inversedBy="commentViewers")
     */
    private $candidateLink;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateVacancy", inversedBy="commentViewers")
     */
    private $candidateVacancy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getVacancyViewerUser(): ?VacancyViewerUser
    {
        return $this->vacancyViewerUser;
    }

    public function setVacancyViewerUser(?VacancyViewerUser $vacancyViewerUser): self
    {
        $this->vacancyViewerUser = $vacancyViewerUser;

        return $this;
    }

    public function getCandidateLink(): ?CandidateLink
    {
        return $this->candidateLink;
    }

    public function setCandidateLink(?CandidateLink $candidateLink): self
    {
        $this->candidateLink = $candidateLink;

        return $this;
    }

    public function getCandidateVacancy(): ?CandidateVacancy
    {
        return $this->candidateVacancy;
    }

    public function setCandidateVacancy(?CandidateVacancy $candidateVacancy): self
    {
        $this->candidateVacancy = $candidateVacancy;

        return $this;
    }
}
