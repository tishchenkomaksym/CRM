<?php

namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate;

use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\FormVacancyCandidateBuilder;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\NonExistsCandidateBuilder;


/**
 * @property FormVacancyCandidateBuilder builder
 */
class StrategyNonExistence implements StrategyForCandidateRelationInterface
{
    private $builder;

    private $nonExistsCandidateBuilder;


    public function __construct(FormVacancyCandidateBuilder $builder,
                                NonExistsCandidateBuilder $nonExistsCandidateBuilder)
    {
        $this->builder = $builder;
        $this->nonExistsCandidateBuilder = $nonExistsCandidateBuilder;
    }


    public function addCandidateVacancy(Vacancy $vacancy,
                                        Candidate $candidate,
                                        CandidateVacancy $candidateVacancy, string $from): void
    {
        $this->builder->build($candidateVacancy, $candidate, $vacancy, $from);
    }

    public function getCandidate(string $name, string $surname): Candidate
    {
        return $this->nonExistsCandidateBuilder->build($name, $surname);
    }

    public function checkCandidateVacancyRelation(Vacancy $vacancy, Candidate $candidate): bool
    {
        return true;
    }
}