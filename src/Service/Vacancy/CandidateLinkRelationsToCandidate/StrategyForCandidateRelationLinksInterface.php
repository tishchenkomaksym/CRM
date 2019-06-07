<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\VacancyLink;

interface StrategyForCandidateRelationLinksInterface
{
    public function addCandidateVacancy(VacancyLink $vacancyLink,
                                        Candidate $candidate,
                                        CandidateLink $candidateLink, string $from);

    public function getCandidate(string $name, string $surname, VacancyLink $vacancyLink, string $from, $receivedCv);

    public function checkCandidateVacancyRelation(VacancyLink $vacancyLink, Candidate $candidate): bool;
}