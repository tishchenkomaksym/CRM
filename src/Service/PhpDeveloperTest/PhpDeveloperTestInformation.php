<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/10/2019
 * Time: 11:54 AM
 */

namespace App\Service\PhpDeveloperTest;

use App\Entity\PhpDeveloperLevelTest;
use App\Service\PhpDeveloperTest\TechnicalComponents\TechnicalComponent;

class PhpDeveloperTestInformation
{
    private $test;
    private $passed;
    /**
     * @var TechnicalComponent[]
     */
    private $requiredComponents;

    /**
     * PhpDeveloperTestInformation constructor.
     * @param PhpDeveloperLevelTest $test
     * @param bool $isPassed
     * @param TechnicalComponent[] $requiredComponents
     */
    public function __construct(PhpDeveloperLevelTest $test, bool $isPassed, array $requiredComponents)
    {
        $this->test = $test;
        $this->passed = $isPassed;
        $this->requiredComponents = $requiredComponents;
    }

    /**
     * @return PhpDeveloperLevelTest
     */
    public function getTest(): PhpDeveloperLevelTest
    {
        return $this->test;
    }
    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->passed;
    }

    /**
     * @return TechnicalComponent[]
     */
    public function getRequiredComponents(): array
    {
        return $this->requiredComponents;
    }

    /**
     * @param TechnicalComponent|array $requiredComponents
     */
    public function setRequiredComponents($requiredComponents): void
    {
        $this->requiredComponents = $requiredComponents;
    }
}
