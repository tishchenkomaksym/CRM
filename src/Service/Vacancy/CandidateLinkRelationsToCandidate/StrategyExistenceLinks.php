<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancyHistory;
use App\Entity\VacancyLink;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\ExistsCandidateBuilderLinks;
use App\Service\Vacancy\CandidateLinkRelationsToCandidate\VacancyCandidateBuilder\FormVacancyCandidateBuilderLinks;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class StrategyExistenceLinks extends AbstractController implements StrategyForCandidateRelationLinksInterface
{
    private $builder;
    /**
     * @var ExistsCandidateBuilderLinks
     */
    private $existsCandidateBuilder;

    /**
     * @var ObjectManager
     */
    private $entityManager;


    public function __construct(FormVacancyCandidateBuilderLinks $builder,
                                ExistsCandidateBuilderLinks $existsCandidateBuilder,
                                ObjectManager $entityManager)
    {
        $this->builder = $builder;
        $this->existsCandidateBuilder = $existsCandidateBuilder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param VacancyLink $vacancyLink
     * @param Candidate $candidate
     * @param CandidateLink $candidateLink
     * @param string $from
     * @throws Exception
     */
    public function addCandidateVacancy(VacancyLink $vacancyLink,
                                        Candidate $candidate,
                                        Candidatelink $candidateLink, string $from): void
    {
        $candidateVacancyHistory = new CandidateVacancyHistory();
        $candidateVacancyHistory
            ->setCandidateStatus('CV Received')
            ->setUpdatedAt(new DateTime())
            ->setCandidateLink($candidateLink);
        $this->entityManager->persist($candidateVacancyHistory);

        $this->builder->build($candidateLink, $candidate, $vacancyLink, $from);
        $this->entityManager->persist($candidateLink);
        $this->entityManager->flush();
    }

    public function getCandidate(string $name, string $surname, VacancyLink $vacancyLink, string $from, $receivedCv): Candidate
    {

        return $this->existsCandidateBuilder->build($name, $surname);
    }


    public function checkCandidateVacancyRelation(VacancyLink $vacancyLink, Candidate $candidate): bool
    {
        return true;
    }
}