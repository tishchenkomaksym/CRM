<?php


namespace App\Service\Vacancy\CandidateApproveAfterInterview\FormValidatorsAfterInterview;


use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use App\Repository\CandidateManagerApprovalRepository;
use App\Repository\CandidateManagerDenyRepository;

class CandidateManagerApprovalExistenceLogic
{

    public const CANDIDATE_LINK = 'candidateLink';

    public const CANDIDATE_VACANCY = 'candidateVacancy';
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

    public function existence(CandidateVacancy $candidateVacancy): array
    {
        $candidateVacancies = [];
//        if ($candidateVacancy !== null){
            if ($this->managerApprovalRepository->findOneBy([self::CANDIDATE_VACANCY => $candidateVacancy->getId()]) !== null) {
                $candidateVacancies[] = $this->managerApprovalRepository->findOneBy([self::CANDIDATE_VACANCY => $candidateVacancy->getId()]);
            }
            if($this->candidateManagerDenyRepository->findOneBy([self::CANDIDATE_VACANCY => $candidateVacancy->getId()]) !== null){
                $candidateVacancies[] = $this->candidateManagerDenyRepository->findOneBy([self::CANDIDATE_VACANCY => $candidateVacancy->getId()]);
            }
//        }
        return $candidateVacancies;
    }

    public function existenceCandidateLink(CandidateLink $candidateLink): array
    {
        $candidateVacancies = [];
//        if ($candidateLink !== null) {
            if ($this->managerApprovalRepository->findOneBy([self::CANDIDATE_LINK => $candidateLink->getId()]) !== null) {
                $candidateVacancies[] = $this->managerApprovalRepository->findOneBy([self::CANDIDATE_LINK => $candidateLink->getId()]);
            }
            if ($this->candidateManagerDenyRepository->findOneBy([self::CANDIDATE_LINK => $candidateLink->getId()]) !== null) {
                $candidateVacancies[] = $this->candidateManagerDenyRepository->findOneBy([self::CANDIDATE_LINK => $candidateLink->getId()]);
            }
//        }
        return $candidateVacancies;
    }

}