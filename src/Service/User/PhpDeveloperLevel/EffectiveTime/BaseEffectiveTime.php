<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 14:36
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime;

class BaseEffectiveTime
{
    /**
     * @var float
     */
    private $requiredTime = 0;
    /**
     * @var float
     */
    private $spendEffectiveTime = 0;

    /** @var bool */
    private $passed;

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
    public function getSpendEffectiveTime(): float
    {
        return $this->spendEffectiveTime;
    }

    /**
     * @param float $spendEffectiveTime
     */
    public function setSpendEffectiveTime(float $spendEffectiveTime): void
    {
        $this->spendEffectiveTime = $spendEffectiveTime;
    }

    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->passed;
    }

    /**
     * @param bool $passed
     */
    public function setPassed(bool $passed): void
    {
        $this->passed = $passed;
    }
}
