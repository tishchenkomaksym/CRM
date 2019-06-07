<?php


namespace App\Service\CandidateVacancyHistory;


use App\Entity\CandidateVacancyHistory;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;

class CandidateVacancyHistoryDataProvider
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function candidateVacancyCreate($candidateVacancy, $status): void
    {
        $candidateVacancyHistory = new CandidateVacancyHistory();
        $candidateVacancyHistory
            ->setCandidateStatus($status)
            ->setUpdatedAt(new DateTime())
            ->setCandidateVacancy($candidateVacancy);
        $this->flushCandidateVacancyHistory($candidateVacancyHistory);
    }

    public function candidateLinkCreate($candidateLink, $status): void
    {
        $candidateVacancyHistory = new CandidateVacancyHistory();
        $candidateVacancyHistory
            ->setCandidateStatus($status)
            ->setUpdatedAt(new DateTime())
            ->setCandidateLink($candidateLink);
        $this->flushCandidateVacancyHistory($candidateVacancyHistory);
    }

    private function flushCandidateVacancyHistory($candidateVacancyHistory): void
    {
        $this->entityManager->persist($candidateVacancyHistory);
        $this->entityManager->flush();
    }


}