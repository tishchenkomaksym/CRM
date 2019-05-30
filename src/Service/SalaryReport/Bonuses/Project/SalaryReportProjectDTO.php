<?php


namespace App\Service\SalaryReport\Bonuses\Project;


use App\Entity\User;

class SalaryReportProjectDTO
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var float
     */
    private $estimate;
    /**
     * @var User
     */
    private $user;

    /**
     * @var  float
     */
    private $spendTime;

    /**
     * @var float
     */
    private $bonusAmount;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SalaryReportProjectDTO
     */
    public function setName(string $name): SalaryReportProjectDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return SalaryReportProjectDTO
     */
    public function setKey(string $key): SalaryReportProjectDTO
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return float
     */
    public function getEstimate(): float
    {
        return $this->estimate;
    }

    /**
     * @param float $estimate
     * @return SalaryReportProjectDTO
     */
    public function setEstimate(float $estimate): SalaryReportProjectDTO
    {
        $this->estimate = $estimate;
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
     * @return SalaryReportProjectDTO
     */
    public function setUser(User $user): SalaryReportProjectDTO
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpendTime(): float
    {
        return $this->spendTime;
    }

    /**
     * @param float $spendTime
     * @return SalaryReportProjectDTO
     */
    public function setSpendTime(float $spendTime): SalaryReportProjectDTO
    {
        $this->spendTime = $spendTime;
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
     * @return SalaryReportProjectDTO
     */
    public function setBonusAmount(float $bonusAmount): SalaryReportProjectDTO
    {
        $this->bonusAmount = $bonusAmount;
        return $this;
    }


}