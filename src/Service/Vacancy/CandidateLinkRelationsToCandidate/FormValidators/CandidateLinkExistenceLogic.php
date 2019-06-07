<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate\FormValidators;

use App\Entity\VacancyLink;
use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Repository\VacancyLinkRepository;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;

class CandidateLinkExistenceLogic
{
    private $candidateExistence;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateLinkRepository;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancyRepository;
    /**
     * @var VacancyLinkRepository
     */
    private $vacancyLinkRepository;

    public function __construct(CandidateLinkRepository $candidateLinkRepository,
                                CandidateVacancyRepository $candidateVacancyRepository,
                                VacancyLinkRepository $vacancyLinkRepository)
    {
        $this->candidateLinkRepository = $candidateLinkRepository;
        $this->candidateVacancyRepository = $candidateVacancyRepository;
        $this->vacancyLinkRepository = $vacancyLinkRepository;
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

    /**
     * @param int $id
     * @param $vacancyIds
     * @return array
     * @throws NoDataException
     */

    public function existenceVacancy(int $id, $vacancyIds): array
    {
        if ($this->vacancyLinkObject($vacancyIds) === null){
            throw new NoDataException('VacancyLink not found');
        }
        if ($this->vacancyLinkObject($vacancyIds)->getVacancy() === null){
            throw new NoDataException('Vacancy not found');
        }
        return $this->candidateExistence = $this->candidateVacancyRepository->findBy(
            [
                'candidate' => $id,
                'vacancy' => $this->vacancyLinkObject($vacancyIds)->getVacancy()->getId()
            ]
        );

    }

    public function vacancyLinkObject($vacancyIds): ?VacancyLink
    {
        return  $this->vacancyLinkRepository->findOneBy(
            [
                'id' => $vacancyIds

            ]
        );
    }
}