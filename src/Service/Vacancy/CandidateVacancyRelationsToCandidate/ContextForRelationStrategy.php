<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use Doctrine\Common\Persistence\ObjectManager;

class ContextForRelationStrategy
{

    private $strategy;
    /**
     * @var ObjectManager
     */
    private $objectManager;


    public function __construct(StrategyForCandidateRelationInterface $strategy, ObjectManager $objectManager)
    {
        $this->strategy = $strategy;
        $this->objectManager = $objectManager;
    }

    public function execute(Vacancy $vacancy,
                            CandidateVacancy $candidateVacancy,
                            string $name,
                            string $surname, string $from): Candidate
    {
        $candidate = $this->strategy->getCandidate($name, $surname);
        $this->strategy->checkCandidateVacancyRelation($vacancy, $candidate);
        $this->strategy->addCandidateVacancy($vacancy, $candidate, $candidateVacancy, $from);
        if ($vacancy->getStatus() === 'Issue have been assigned') {
            $vacancy->setStatus('CV Received');
            $this->objectManager->flush();
        }
        return $candidate;
    }

}