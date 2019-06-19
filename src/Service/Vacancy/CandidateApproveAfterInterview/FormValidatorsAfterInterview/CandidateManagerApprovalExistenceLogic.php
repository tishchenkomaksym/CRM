<?php


namespace App\Service\Vacancy\CandidateApproveAfterInterview\FormValidatorsAfterInterview;


use App\Entity\CandidateLink;
use App\Entity\CandidateManagerDeny;
use App\Entity\CandidateVacancy;
use App\Repository\CandidateManagerApprovalRepository;
use App\Repository\CandidateManagerDenyRepository;

class CandidateManagerApprovalExistenceLogic
{


    /**
     * @var CandidateManagerApprovalRepository
     */
    private $managerApprovalRepository;
    /**
     * @var CandidateManagerDenyRepository
     */
    private $candidateManagerDenyRepository;

    public function __construct(CandidateManagerApprovalRepository $managerApprovalRepository,
                                CandidateManagerDenyRepository $candidateManagerDenyRepository)
    {

        $this->managerApprovalRepository = $managerApprovalRepository;
        $this->candidateManagerDenyRepository = $candidateManagerDenyRepository;
    }

    public function existence($candidateLink, $candidateVacancy): array
    {
        $candidateVacancies = [];
        if ($candidateLink !== null) {
            if ($this->managerApprovalRepository->findOneBy(['candidateLink' => $candidateLink->getId()]) !== null) {
                $candidateVacancies[] = $this->managerApprovalRepository->findOneBy(['candidateLink' => $candidateLink->getId()]);
            }
            if ($this->candidateManagerDenyRepository->findOneBy(['candidateLink' => $candidateLink->getId()]) !== null) {
                $candidateVacancies[] = $this->candidateManagerDenyRepository->findOneBy(['candidateLink' => $candidateLink->getId()]);
            }
        }
        if ($candidateVacancy !== null){
            if ($this->managerApprovalRepository->findOneBy(['candidateVacancy' => $candidateVacancy->getId()]) !== null) {
                $candidateVacancies[] = $this->managerApprovalRepository->findOneBy(['candidateVacancy' => $candidateVacancy->getId()]);
            }
            if($this->candidateManagerDenyRepository->findOneBy(['candidateVacancy' => $candidateVacancy->getId()]) !== null){
                $candidateVacancies[] = $this->candidateManagerDenyRepository->findOneBy(['candidateVacancy' => $candidateVacancy->getId()]);;
            }
        }
        return $candidateVacancies;
    }

}