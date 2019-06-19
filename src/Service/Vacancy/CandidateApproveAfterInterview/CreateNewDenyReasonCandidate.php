<?php


namespace App\Service\Vacancy\CandidateApproveAfterInterview;

use App\Entity\CandidateOfferDeny;
use App\Entity\DenyReasonCandidate;
use App\Repository\DenyChoiceCandidateRepository;
use App\Repository\DenyChoiceDepartmentRepository;
use Doctrine\Common\Persistence\ObjectManager;

class CreateNewDenyReasonCandidate
{
    /**
     * @var DenyChoiceDepartmentRepository
     */
    private $denyChoiceCandidateRepository;
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(DenyChoiceCandidateRepository $denyChoiceCandidateRepository,
        ObjectManager $entityManager)
    {
        $this->denyChoiceCandidateRepository = $denyChoiceCandidateRepository;
        $this->entityManager = $entityManager;
    }

    public function newDenyReasonCandidate($choices, CandidateOfferDeny $candidateOfferDeny): void
    {
        $allChoiceDepartment = $this->denyChoiceCandidateRepository->findAll();
        $array = [];
        foreach ($allChoiceDepartment as $choiceDepartment) {
            foreach ($choices as $choice) {
                if ($choiceDepartment->getId() === $choice->getId()) {
                    $array[] = $choiceDepartment;
                }
            }
        }
        foreach ($array as $arr) {
            $denyReasonCandidate = new DenyReasonCandidate();
            $this->entityManager->persist($denyReasonCandidate->setDenyChoiceCandidate($arr));
            $this->entityManager->persist($denyReasonCandidate->setCandidateOfferDeny($candidateOfferDeny));
        }
    }

    public function notSuitableSalary($choices): array
    {
        $array = [];
        foreach ($choices as $choice) {
            if ($choice->getid() === 1) {
                $array[] = $choice;
            }
        }
        return $array;
    }
}