<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateVacancy;
use App\Entity\Vacancy;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\ExistsCandidateBuilder;
use App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder\FormVacancyCandidateBuilder;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class StrategyExistence extends AbstractController implements StrategyForCandidateRelationInterface
{
    private $builder;
    /**
     * @var ExistsCandidateBuilder
     */
    private $existsCandidateBuilder;

    /**
     * @var ObjectManager
     */
    private $entityManager;


    public function __construct(FormVacancyCandidateBuilder $builder,
                                ExistsCandidateBuilder $existsCandidateBuilder,
                                ObjectManager $entityManager)
    {
        $this->builder = $builder;
        $this->existsCandidateBuilder = $existsCandidateBuilder;
        $this->entityManager = $entityManager;
    }

    public function addCandidateVacancy(Vacancy $vacancy,
                                        Candidate $candidate,
                                        CandidateVacancy $candidateVacancy, string $from): void
    {
        $this->builder->build($candidateVacancy, $candidate, $vacancy, $from);
        $this->entityManager->persist($candidateVacancy);
        $this->entityManager->flush();
    }

    public function getCandidate(string $name, string $surname): Candidate
    {

        return $this->existsCandidateBuilder->build($name, $surname);
    }


    public function checkCandidateVacancyRelation(Vacancy $vacancy, Candidate $candidate): bool
    {
        return true;
    }
}