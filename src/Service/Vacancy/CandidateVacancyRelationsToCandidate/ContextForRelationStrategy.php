<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;


class ContextForRelationStrategy
{

    private $strategy;


    public function __construct(StrategyForCandidateRelationInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function execute(Vacancy $vacancy,
                            CandidateVacancy $candidateVacancy,
                            string $name,
                            string $surname, string $from, $receivedCv): Candidate
    {
        $candidate = $this->strategy->getCandidate($name, $surname, $vacancy, $from, $receivedCv);
        $this->strategy->checkCandidateVacancyRelation($vacancy, $candidate);
        $this->strategy->addCandidateVacancy($vacancy, $candidate, $candidateVacancy, $from);

        return $candidate;
    }

}