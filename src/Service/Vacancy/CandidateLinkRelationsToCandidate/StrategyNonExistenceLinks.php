<?php

namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate;

use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\VacancyLink;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\FormVacancyCandidateBuilderLinks;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\NonExistsCandidateBuilderLinks;
use Exception;


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


    /**
     * @param VacancyLink $vacancyLink
     * @param Candidate $candidate
     * @param CandidateLink $candidateLink
     * @param string $from
     * @throws Exception
     */
    public function addCandidateVacancy(VacancyLink $vacancyLink,
                                        Candidate $candidate,
                                        CandidateLink $candidateLink, string $from): void
    {

    }

    public function getCandidate(string $name, string $surname, VacancyLink $vacancyLink, string $from, $receivedCv): Candidate
    {
        return $this->nonExistsCandidateBuilder->build($name, $surname, $vacancyLink, $from, $receivedCv);
    }

    public function checkCandidateVacancyRelation(VacancyLink $vacancyLink, Candidate $candidate): bool
    {
        return true;
    }
}