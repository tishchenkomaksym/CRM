<?php


namespace App\Service\CandidateForms;


use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
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
    /**
     * @var VacancyLinkRepository
     */
    private $vacancyLinkRepository;

    public function __construct(CandidateVacancyRepository $candidateVacancyRepository,
                                CandidateLinkRepository $candidateLinkRepository,
                                VacancyLinkRepository $vacancyLinkRepository)
    {
        $this->candidateVacancyRepository = $candidateVacancyRepository;
        $this->candidateLinkRepository = $candidateLinkRepository;
        $this->vacancyLinkRepository = $vacancyLinkRepository;
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

    public function vacancyLink(Vacancy $vacancy):array
    {
        return $this->vacancyLinkRepository->findBy([
           'vacancy' => $vacancy->getId()
        ]);
    }

    public function candidateVacancyByIdSearch($candidateVacancyId): ?CandidateVacancy
    {
        return $this->candidateVacancyRepository->findOneBy(['id' => $candidateVacancyId]);
    }

    public function candidateLinkByIdSearch($candidateLinkId): ?CandidateLink
    {
        return $this->candidateLinkRepository->findOneBy(['id' => $candidateLinkId]);
    }
}