<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 05.03.2019
 * Time: 18:11
 */

namespace App\Service\PhpDeveloperTest\TechnicalComponents;

class TechnicalComponent
{
    private $spendHours;
    private $requiredHours;
    private $name;

    /**
     * @return float
     */
    public function getSpendHours(): float
    {
        return $this->spendHours;
    }

    /**
     * @param float $spendHours
     * @return TechnicalComponent
     */
    public function setSpendHours(float $spendHours): TechnicalComponent
    {
        $this->spendHours = $spendHours;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiredHours()
    {
        return $this->requiredHours;
    }

    /**
     * @param mixed $requiredHours
     * @return TechnicalComponent
     */
    public function setRequiredHours(float $requiredHours): TechnicalComponent
    {
        $this->requiredHours = $requiredHours;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TechnicalComponent
     */
    public function setName(string $name): TechnicalComponent
    {
        $this->name = $name;

        return $this;
    }
}
