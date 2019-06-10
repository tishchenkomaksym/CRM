<?php


namespace App\Service\Candidate;


use App\Entity\CandidateVacancy;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VacancyFieldDecorator
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(ObjectManager $objectManager, TokenStorageInterface $tokenStorage)
    {
        $this->objectManager = $objectManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function vacancyField($vacancyIds, $candidate): void
    {
        foreach ($vacancyIds as $vacancyId) {
            $candidateVacancy = new CandidateVacancy();
            $candidateVacancy->setCandidate($candidate);
            $candidateVacancy->setVacancy($vacancyId);
            $candidateVacancy->setCreatedBy($this->tokenStorage->getToken()->getUser());
            $candidateVacancy->setCreatedAt(new DateTimeImmutable('now'));
            $this->objectManager->persist($candidateVacancy);
        }
    }
}