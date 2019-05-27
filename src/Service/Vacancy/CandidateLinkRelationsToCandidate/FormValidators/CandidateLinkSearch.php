<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;


use App\Entity\CandidateLink;
use App\Entity\Vacancy;
use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;

class CandidateLinkSearch
{
    private $search;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateLinkRepository;

    public function __construct(CandidateLinkRepository $candidateLinkRepository)
    {
        $this->candidateLinkRepository = $candidateLinkRepository;
    }

    public function searchCandidateLink(int $id, Vacancy $vacancy): ?CandidateLink
    {

        return $this->search = $this->candidateLinkRepository->findOneBy(
            [
                'candidate' => $id,
                'vacancyLink' => $vacancy->getVacancyLinks()->current()->getId()
            ]
        );

    }
}