<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/10/2019
 * Time: 11:51 AM
 */

namespace App\Service\PhpDeveloperTest;

use App\Entity\User;

class PhpDeveloperTestsInformationBuilder
{
    /**
     * @param User $user
     * @return PhpDeveloperTestInformation[] array
     */
    public static function build(User $user): array
    {
        $returnArray = [];
        $phpDeveloperRelation = $user->getPhpDeveloperLevelRelation();
        $allTestsByLevel = [];
        if ($phpDeveloperRelation) {
            $phpLevel = $phpDeveloperRelation->getPhpDeveloperLevel();
            if ($phpLevel) {
                $allTestsByLevel = $phpLevel->getPhpDeveloperLevelTests();
            }
        }
        $passedIds = [];
        $passedTests = $user->getPhpDeveloperLevelTestsPassed();
        foreach ($passedTests as $passedTest) {
            $phpDeveloperTest = $passedTest->getPhpDeveloperLevelTest();
            if ($phpDeveloperTest) {
                $id = $phpDeveloperTest->getId();
                $passedIds[$id] = $id;
            }
        }

        foreach ($allTestsByLevel as $developerLevelTest) {
            $isPassed = isset($passedIds[$developerLevelTest->getId()]);
            $returnArray[] = new PhpDeveloperTestInformation($developerLevelTest, $isPassed);
        }
        return $returnArray;
    }
}
