<?php


namespace App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Candidate;
use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Repository\VacancyRepository;
use Doctrine\Common\Persistence\ObjectManager;

class CandidateEditRelations
{

    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var VacancyRepository
     */
    private $vacancyRepository;

    public const CV_RECEIVED = 'CV Received';
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancyRepository;
    /**
     * @var CandidateLinkRepository
     */
    private $candidateLinkRepository;

    public function __construct(ObjectManager $entityManager,
                                VacancyRepository $vacancyRepository,
                                CandidateVacancyRepository $candidateVacancyRepository,
                                CandidateLinkRepository $candidateLinkRepository)
    {
        $this->entityManager = $entityManager;
        $this->vacancyRepository = $vacancyRepository;
        $this->candidateVacancyRepository = $candidateVacancyRepository;
        $this->candidateLinkRepository = $candidateLinkRepository;
    }

    /**
     * @param Candidate $candidate
     * @param $vacancyId
     * @return int|null
     * @throws NoDataException
     * @throws NoDateException
     */
    public function candidateFromHunting(Candidate $candidate,
                                        $vacancyId): ?int
    {
        $candidateVacancy = $this->candidateVacancyRepository->findOneBy([
            'candidate' => $candidate->getId(),
            'vacancy' => $vacancyId
        ]);
        if ($candidateVacancy === null) {
            throw new NoDataException('CandidateVacancy Not found');
        }

        if ($candidateVacancy !== null){

            $vacancy = $this->vacancyRepository->findOneBy(['id' => $vacancyId]);
            if ($vacancy === null){
                throw new NoDateException('Vacancy not found');
            }
            $this->entityManager->persist($candidateVacancy->setCandidateStatus(self::CV_RECEIVED));
            $this->entityManager->flush();
        }
        return $vacancyId;
    }

    /**
     * @param Candidate $candidate
     * @param $vacancyLinkId
     * @return int|null
     * @throws NoDataException
     * @throws NoDateException
     */
    public function candidateFromVacancyRecommendation(Candidate $candidate, $vacancyLinkId): ?int
    {
        $candidateLink = $this->candidateLinkRepository->findOneBy([
            'candidate' => $candidate->getId(),
            'vacancyLink' => $vacancyLinkId
        ]);

        if ($candidateLink === null) {
            throw new NoDataException('CandidateLink Not found');
        }

        if ($candidateLink->getVacancyLink() === null) {
        throw new NoDateException('VacancyLink not found');
    }

        if ($candidateLink->getVacancyLink()->getVacancy() === null) {
            throw new NoDateException('Vacancy not found');
        }
        $this->entityManager->persist($candidateLink->setCandidateStatus(self::CV_RECEIVED));
            $this->entityManager->flush();

            return $candidateLink->getVacancyLink()->getVacancy()->getId();
        }
}