<?php


namespace App\Service\Vacancy\CreateCandidateVacancyLinkForLetter;


use App\Entity\Candidate;
use App\Entity\Vacancy;

interface CandidateLinkVacancyInterface
{

    public function vacancy():Vacancy;

    public function vacancyCreatedAt();

    public function vacancyAssignedToEmail();

    public function vacancyApproveDate();

    public function vacancyApprovedByEmail();

    public function confRoom();

    public function candidate():Candidate;

    public function departmentManagerEmail();

    public function viewerEmail();

    public function recruiterEmail();

    public function dateInterview();

    public function dateStartWork();

    public function candidateManagerApproval();
}