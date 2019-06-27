<?php


namespace App\Service\Vacancy\CandidateVacancyAmount;


use App\Entity\Vacancy;
use Doctrine\Common\Persistence\ObjectManager;

class CandidateVacancyAmountCheckLogic
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function checkCandidateVacancyAmount(Vacancy $vacancy): array
    {
        $candidates = [];
        foreach($vacancy->getVacancyLinks() as $vacancyLink){
            foreach($vacancyLink->getCandidateLinks() as $candidateLink){
                if ($candidateLink->getCandidateStatus() === 'Employed'){
                    $candidates[] = $candidateLink;
                }
            }
        }
        foreach($vacancy->getCandidateVacancies() as $candidateVacancy){
            if ($candidateVacancy->getCandidateStatus() === 'Employed'){
                $candidates[] = $candidateVacancy;
            }
        }
        return $candidates;
    }

    public function changeVacancyStatus(Vacancy $vacancy): void
    {
        if (count($this->checkCandidateVacancyAmount($vacancy)) === $vacancy->getAmount()){
            $vacancy->setStatus('Closed');
            $this->objectManager->persist($vacancy);
            $this->objectManager->flush();
        }
    }
}