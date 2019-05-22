<?php

namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate;

use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\VacancyLink;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\FormVacancyCandidateBuilderLinks;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\NonExistsCandidateBuilderLinks;


/**
 * @property FormVacancyCandidateBuilderLinks builder
 */
class StrategyNonExistenceLinks implements StrategyForCandidateRelationLinksInterface
{
    private $builder;

    private $nonExistsCandidateBuilder;


    public function __construct(FormVacancyCandidateBuilderLinks $builder,
                                NonExistsCandidateBuilderLinks $nonExistsCandidateBuilder)
    {
        $this->builder = $builder;
        $this->nonExistsCandidateBuilder = $nonExistsCandidateBuilder;
    }


    public function addCandidateVacancy(VacancyLink $vacancyLink,
                                        Candidate $candidate,
                                        CandidateLink $candidateLink, string $from): void
    {
        $this->builder->build($candidateLink, $candidate, $vacancyLink, $from);
    }

    public function getCandidate(string $name, string $surname): Candidate
    {
        return $this->nonExistsCandidateBuilder->build($name, $surname);
    }

    public function checkCandidateVacancyRelation(VacancyLink $vacancyLink, Candidate $candidate): bool
    {
        return true;
    }
}