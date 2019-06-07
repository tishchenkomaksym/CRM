<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\VacancyLink;


class ContextForRelationStrategyLinks
{

    private $strategy;


    public function __construct(StrategyForCandidateRelationLinksInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function execute(VacancyLink $vacancyLink,
                            CandidateLink $candidateLink,
                            string $name,
                            string $surname, string $from, $receivedCv): Candidate
    {
        $candidate = $this->strategy->getCandidate($name, $surname, $vacancyLink, $from, $receivedCv);
        $this->strategy->checkCandidateVacancyRelation($vacancyLink, $candidate);
        $this->strategy->addCandidateVacancy($vacancyLink, $candidate, $candidateLink, $from);

        return $candidate;
    }

}