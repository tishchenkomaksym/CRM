<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\FormValidators;

use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Repository\VacancyRepository;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;

class CandidateVacancyExistenceLogic
{
    private $candidateExistence;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancyRepository;
    /**
     * @var CandidateLinkRepository
     */
    private $candidateLinkRepository;
    /**
     * @var VacancyRepository
     */
    private $vacancyRepository;

    public function __construct(CandidateVacancyRepository $candidateVacancyRepository,
                                CandidateLinkRepository $candidateLinkRepository,
                                VacancyRepository $vacancyRepository)
    {
        $this->candidateVacancyRepository = $candidateVacancyRepository;
        $this->candidateLinkRepository = $candidateLinkRepository;
        $this->vacancyRepository = $vacancyRepository;
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

    /**
     * @param int $id
     * @param $vacancyIds
     * @return array
     * @throws NoDataException
     */
    public function existenceLink(int $id, $vacancyIds): array
    {

        return $this->candidateExistence = $this->candidateLinkRepository->findBy(
            [
                'candidate' => $id,
                'vacancyLink' => $this->vacancyLinks($vacancyIds)
            ]
        );

    }

    /**
     * @param $vacancyIds
     * @return array
     * @throws NoDataException
     */
    public function vacancyLinks($vacancyIds): array
    {
        $vacancyLinks = [];
        $vacancy = $this->vacancyRepository->findOneBy(['id' => $vacancyIds]);
        if ($vacancy === null){
            throw new NoDataException('Vacancy not found');
        }
        foreach ($vacancy->getVacancyLinks() as $vacancyLink){
            $vacancyLinks[] = $vacancyLink;
        }
        return $vacancyLinks;
    }
}