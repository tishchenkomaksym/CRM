<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder;


use App\Entity\Candidate;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;

class NonExistsCandidateBuilder
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function build(string $name,
                          string $surname): Candidate
    {
        $candidate = new Candidate();
        $candidate
            ->setName($name)
            ->setSurname($surname)
            ->setPhone('')
            ->setPosition('')
            ->setLocation('')
            ->setEmail('')
            ->setCreatedAt(new DateTimeImmutable('now'));
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();
        return $candidate;
    }
}