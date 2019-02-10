<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/10/2019
 * Time: 11:54 AM
 */

namespace App\Service\PhpDeveloperTest;

use App\Entity\PhpDeveloperLevelTest;

class PhpDeveloperTestInformation
{
    private $test;
    private $passed;

    public function __construct(PhpDeveloperLevelTest $test, bool $isPassed)
    {
        $this->test = $test;
        $this->passed = $isPassed;
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
}
