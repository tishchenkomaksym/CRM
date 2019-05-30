<?php


namespace App\Service\SalaryReport\Bonuses\User;


use App\Entity\User;
use App\Service\SalaryReport\Bonuses\Project\SalaryReportProjectDTO;

class UserBonusInformation
{
    /**
     * @var SalaryReportProjectDTO[]
     */
    private $projects;
    /**
     * @var float
     */
    private $bonusAmount;
    /**
     * @var User
     */
    private $user;

    /**
     * @return SalaryReportProjectDTO[]
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @param SalaryReportProjectDTO[] $projects
     * @return UserBonusInformation
     */
    public function setProjects(array $projects): UserBonusInformation
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * @return float
     */
    public function getBonusAmount(): float
    {
        return $this->bonusAmount;
    }

    /**
     * @param float $bonusAmount
     * @return UserBonusInformation
     */
    public function setBonusAmount(float $bonusAmount): UserBonusInformation
    {
        $this->bonusAmount = $bonusAmount;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserBonusInformation
     */
    public function setUser(User $user): UserBonusInformation
    {
        $this->user = $user;
        return $this;
    }
}