<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;

interface StrategyForCandidateRelationInterface
{
    public function addCandidateVacancy(Vacancy $vacancy,
                                        Candidate $candidate,
                                        CandidateVacancy $candidateVacancy, string $from);

    public function getCandidate(string $name, string $surname);

    public function checkCandidateVacancyRelation(Vacancy $vacancy, Candidate $candidate): bool;
}