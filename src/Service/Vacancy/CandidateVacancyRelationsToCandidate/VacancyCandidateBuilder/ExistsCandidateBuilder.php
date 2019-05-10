<?php


namespace App\Service\Vacancy\CandidateVacancyRelationsToCandidate\VacancyCandidateBuilder;


use App\Repository\CandidateRepository;
use App\Repository\CandidateVacancyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ExistsCandidateBuilder extends AbstractController
{
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    public function build(string $name, string $surname)
    {
        return $this->candidateRepository->findOneBy(
            [
                'name' => $name,
                'surname' => $surname
            ]
        );
    }
}