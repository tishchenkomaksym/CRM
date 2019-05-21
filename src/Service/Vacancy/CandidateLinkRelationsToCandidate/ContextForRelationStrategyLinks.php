<?php


namespace App\Service\Vacancy\CandidateLinkRelationsToCandidate;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\Vacancy;
use App\Entity\VacancyLink;
use Doctrine\Common\Persistence\ObjectManager;

class ContextForRelationStrategyLinks
{

    private $strategy;
    /**
     * @var ObjectManager
     */
    private $objectManager;


    public function __construct(StrategyForCandidateRelationLinksInterface $strategy, ObjectManager $objectManager)
    {
        $this->strategy = $strategy;
        $this->objectManager = $objectManager;
    }

    public function execute(VacancyLink $vacancyLink,
                            CandidateLink $candidateLink,
                            string $name,
                            string $surname, string $from, Vacancy $vacancy): Candidate
    {
        $candidate = $this->strategy->getCandidate($name, $surname);
        $this->strategy->checkCandidateVacancyRelation($vacancyLink, $candidate);
        $this->strategy->addCandidateVacancy($vacancyLink, $candidate, $candidateLink, $from);
        if ($vacancy->getStatus() === 'Issue have been assigned') {
            $vacancy->setStatus('CV Received');
            $this->objectManager->flush();
        }
        return $candidate;
    }

}