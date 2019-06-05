<?php


namespace App\Service\SalaryReport\Bonuses\Project;


use App\Entity\User;

class SalaryReportProjectDTOBuilder
{
    public function build(ElasticProjectDTO $projectDTO, User $user, float $projectEstimate)
    {
        $object = new SalaryReportProjectDTO();
        $object->setName($projectDTO->getName());
        $object->setKey($projectDTO->getKey());
        $object->setUser($user);
        $object->setEstimate($projectEstimate);
        return $object;
    }
}