<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 20:22
 */

namespace App\Service\User\PhpDeveloper\Hours;

use App\Entity\User;

class WorkHoursInformation
{
    /**
     * @var float
     */
    private $requiredTime = 0;
    /**
     * @var float
     */
    private $loggedTime = 0;

    /**
     * @var User
     */
    private $user;
    /**
     * @return float
     */
    public function getRequiredTime(): float
    {
        return $this->requiredTime;
    }

    /**
     * @param float $requiredTime
     */
    public function setRequiredTime(float $requiredTime): void
    {
        $this->requiredTime = $requiredTime;
    }

    /**
     * @return float
     */
    public function getLoggedTime(): float
    {
        return $this->loggedTime;
    }

    /**
     * @param float $loggedTime
     */
    public function setLoggedTime(float $loggedTime): void
    {
        $this->loggedTime = $loggedTime;
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
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
