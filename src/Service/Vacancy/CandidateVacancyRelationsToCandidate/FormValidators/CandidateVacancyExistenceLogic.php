<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;

use App\Repository\CandidateVacancyRepository;

class CandidateVacancyExistenceLogic
{
    private $candidateExistence;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancyRepository;

    public function __construct(CandidateVacancyRepository $candidateVacancyRepository)
    {
        $this->candidateVacancyRepository = $candidateVacancyRepository;
    }

    public function existence(int $id, $vacancyIds): array
    {

        return $this->candidateExistence = $this->candidateVacancyRepository->findBy(
            [
                'candidate' => $id,
                'vacancy' => $vacancyIds
            ]
        );

    }
}