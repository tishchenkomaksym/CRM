<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 20:22
 */

namespace App\Service\User\PhpDeveloper\Hours;

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
}
