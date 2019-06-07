<?php


namespace App\Service\Vacancy\CandidateApprove;


use App\Entity\Candidate;
use App\Entity\CandidateLink;
use App\Entity\CandidateVacancy;
use App\Entity\CommentViewer;
use App\Entity\Vacancy;
use App\Entity\VacancyViewerUser;
use App\Repository\CommentViewerRepository;
use App\Repository\VacancyRepository;
use App\Repository\VacancyViewerUserRepository;
use App\Service\CandidateForms\CandidateForms;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DepartmentManagerApproveViewDTOBuilder
{
    /**
     * @var VacancyRepository
     */
    private $vacancyRepository;
    /**
     * @var CandidateForms
     */
    private $candidateForms;
    /**
     * @var CommentViewerRepository
     */
    private $commentViewerRepository;
    /**
     * @var VacancyViewerUserRepository
     */
    private $vacancyViewerUserRepository;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        VacancyRepository $vacancyRepository,
        CandidateForms $candidateForms,
        CommentViewerRepository $commentViewerRepository,
        VacancyViewerUserRepository $vacancyViewerUserRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->vacancyRepository = $vacancyRepository;
        $this->candidateForms = $candidateForms;
        $this->commentViewerRepository = $commentViewerRepository;
        $this->vacancyViewerUserRepository = $vacancyViewerUserRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Candidate $candidate
     * @param $vacancyId
     * @return DepartmentManagerApproveViewDTO
     * @throws NoDataException
     */
    public function build(Candidate $candidate, $vacancyId): DepartmentManagerApproveViewDTO
    {

        $dto = new DepartmentManagerApproveViewDTO();
        $dto->setCandidate($candidate)
            ->setVacancy($this->getVacancy($vacancyId));
        $candidateVacancy = $this->candidateForms->candidateVacancySearch($dto->getVacancy(),
            $candidate->getId());
        if ($candidateVacancy !== null){
            $dto->setCandidateVacancy($candidateVacancy);
        }
        $candidateLink = $this->candidateForms->candidateLinkSearch($dto->getVacancy()->getVacancyLinks(),
            $candidate);
        if ($candidateLink !== null){
            $dto->setCandidateLink($candidateLink);
        }
        if (($dto->getCandidateLink() !== null) && $this->getCandidateLinkComment($dto->getCandidateLink()) !== null) {
            $dto->setCheckCandidateLink($this->getCandidateLinkComment($dto->getCandidateLink()));
        }
        if (($dto->getCandidateVacancy() !== null) && $this->getCandidateVacancyComment($dto->getCandidateVacancy()) !== null) {
            $dto->setCheckCandidateVacancy($this->getCandidateVacancyComment($dto->getCandidateVacancy()));
        }


        return $dto;
    }

    /**
     * @param int $vacancyId
     * @return Vacancy
     * @throws NoDataException
     */
    private function getVacancy(int $vacancyId): Vacancy
    {
        $vacancy = $this->vacancyRepository->findOneBy(['id' => $vacancyId]);

        if ($vacancy === null) {
            throw new NoDataException('Vacancy not found');
        }
        return $vacancy;
    }

    /**
     * @return VacancyViewerUser|null
     * @throws NoDataException
     */
    private function  getVacancyViewerUser(): ?VacancyViewerUser
    {
        if ($this->tokenStorage->getToken() === null) {
            throw new NoDataException('User not found');
        }
        return $this->vacancyViewerUserRepository->findOneBy(['user' => $this->tokenStorage->getToken()->getUser()]);

    }

    /**
     * @param CandidateLink $candidateLink
     * @return CommentViewer|null
     * @throws NoDataException
     */
    private function getCandidateLinkComment(CandidateLink $candidateLink): ?CommentViewer
    {
        $candidateLinkComment = null;
        if ($this->getVacancyViewerUser() !== null) {
            $candidateLinkComment = $this->commentViewerRepository->findOneBy([
                'vacancyViewerUser' => $this->getVacancyViewerUser()->getId(),
                'candidateLink' => $candidateLink->getId(),
            ]);
        }
        return $candidateLinkComment;
    }

    /**
     * @param CandidateVacancy $candidateVacancy
     * @return CommentViewer|null
     * @throws NoDataException
     */
    private function getCandidateVacancyComment(CandidateVacancy $candidateVacancy): ?CommentViewer
    {
        if ($this->getVacancyViewerUser() !== null) {
            return $this->commentViewerRepository->findOneBy([
                'vacancyViewerUser' => $this->getVacancyViewerUser()->getId(),
                'candidateVacancy' => $candidateVacancy->getId()
            ]);
        }

        return null;
    }
}