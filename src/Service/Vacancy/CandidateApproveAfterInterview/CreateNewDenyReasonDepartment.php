<?php


namespace App\Service\Vacancy\CandidateApproveAfterInterview;


use App\Entity\CandidateManagerDeny;
use App\Entity\DenyReasonDepartment;
use App\Repository\DenyChoiceDepartmentRepository;
use Doctrine\Common\Persistence\ObjectManager;

class CreateNewDenyReasonDepartment
{

    /**
     * @var DenyChoiceDepartmentRepository
     */
    private $denyChoiceDepartmentRepository;
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(DenyChoiceDepartmentRepository $denyChoiceDepartmentRepository,
                                ObjectManager $entityManager)
    {
        $this->denyChoiceDepartmentRepository = $denyChoiceDepartmentRepository;
        $this->entityManager = $entityManager;
    }

    public function newDenyReasonDepartment($choices, CandidateManagerDeny $candidateManagerDeny): void
    {
        $allChoiceDepartment = $this->denyChoiceDepartmentRepository->findAll();
        $array = [];
        foreach ($allChoiceDepartment as $choiceDepartment) {
            foreach ($choices as $choice) {
                if ($choiceDepartment->getId() === $choice->getId()) {
                    $array[] = $choiceDepartment;
                }
            }
        }
        foreach ($array as $arr) {
            $denyReasonDepartment = new DenyReasonDepartment();
            $this->entityManager->persist($denyReasonDepartment->setDenyChoiceDepartment($arr));
            $this->entityManager->persist($denyReasonDepartment->setCandidateManagerDeny($candidateManagerDeny));
        }
    }
}