<?php


namespace App\Service\CandidateForms;


use App\Entity\CandidateLink;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Repository\VacancyLinkRepository;


class CandidateForms
{
    /**
     * @var VacancyLinkRepository
     */
    private $candidateVacancyRepository;
    /**
       * @var CandidateLinkRepository
    */
    private $candidateLinkRepository;
    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const CANDIDATE = 'candidate';

    public function __construct(CandidateVacancyRepository $candidateVacancyRepository,
                                CandidateLinkRepository $candidateLinkRepository)
    {
        $this->candidateVacancyRepository = $candidateVacancyRepository;
        $this->candidateLinkRepository = $candidateLinkRepository;
    }

    public function candidateVacancySearch(Vacancy $vacancy, $candidateId)
    {
        return $this->candidateVacancyRepository->findOneBy([
            self::CANDIDATE => $candidateId,
            self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
        ]);
    }

    public function candidateVacancyCheckSelection(Vacancy $vacancy): array
    {
        return $this->candidateVacancyRepository->findBy([
            'candidateStatus' => ['Approved for the interview','Closed'],
            self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
        ]);
    }

    public function candidateLinkSearch($vacancyLink, $candidateId): ?CandidateLink
    {
        $vacancyLinks = [];
        foreach ($vacancyLink as $link){
            $vacancyLinks[] = $link;
        }
        return $this->candidateLinkRepository->findOneBy([
            self::CANDIDATE => $candidateId,
            'vacancyLink' => $vacancyLinks
        ]);
    }

    public function candidateLinkCheckSelection(Vacancy $vacancy): array
    {
        return $this->candidateVacancyRepository->findBy([
            'candidateStatus' => ['Approved for the interview','Closed'],
            self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
        ]);
    }
}