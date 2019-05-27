<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;

use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;

class CandidateLinkExistenceLogic
{
    private $candidateExistence;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateLinkRepository;

    public function __construct(CandidateLinkRepository $candidateLinkRepository)
    {
        $this->candidateLinkRepository = $candidateLinkRepository;
    }

    public function existence(int $id, $vacancyIds): array
    {

        return $this->candidateExistence = $this->candidateLinkRepository->findBy(
            [
                'candidate' => $id,
                'vacancyLink' => $vacancyIds
            ]
        );

    }
}