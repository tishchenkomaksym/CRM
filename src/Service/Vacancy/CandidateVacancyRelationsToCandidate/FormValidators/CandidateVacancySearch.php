<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;


use App\Entity\CandidateVacancy;
use App\Repository\CandidateVacancyRepository;

class CandidateVacancySearch
{
    private $search;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancyRepository;

    public function __construct(CandidateVacancyRepository $candidateVacancyRepository)
    {
        $this->candidateVacancyRepository = $candidateVacancyRepository;
    }

    public function searchCandidateVacancy(int $id, $vacancyId): ?CandidateVacancy
    {

        return $this->search = $this->candidateVacancyRepository->findOneBy(
            [
                'candidate' => $id,
                'vacancy' => $vacancyId
            ]
        );

    }
}