<?php


namespace App\Service\Candidate;


use App\Entity\CandidateVacancy;
use Doctrine\Common\Persistence\ObjectManager;

class VacancyFieldDecorator
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function vacancyField($vacancyIds, $candidate): void
    {
        foreach ($vacancyIds as $vacancyId) {
            $candidateVacancy = new CandidateVacancy();
            $candidateVacancy->setCandidate($candidate);
            $candidateVacancy->setVacancy($vacancyId);
            $this->objectManager->persist($candidateVacancy);
        }
    }
}