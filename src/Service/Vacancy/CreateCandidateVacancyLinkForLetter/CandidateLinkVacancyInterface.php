<?php


namespace App\Service\Vacancy\CreateCandidateVacancyLinkForLetter;


use App\Entity\Vacancy;

interface CandidateLinkVacancyInterface
{
    public function vacancyId();

    public function vacancy():Vacancy;

    public function vacancyCreatedAt();

    public function vacancyCreatedByEmail();

    public function vacancyAssignedToEmail();

    public function vacancyApproveDate();

    public function vacancyApprovedByEmail();

    public function confRoom();

    public function candidateId();

    public function candidateName();

    public function candidateSurname();

    public function departmentManagerEmail();

    public function viewerEmail();

    public function recruiterEmail();

    public function dateInterview();
}