<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/10/2019
 * Time: 11:51 AM
 */

namespace App\Service\PhpDeveloperTest;

use App\Entity\User;
use App\Service\PhpDeveloperTest\Exception\NoExistsNewLevelOfDeveloper;
use App\Service\PhpDeveloperTest\TechnicalComponents\TechnicalComponentBuilder;

class PhpDeveloperTestsInformationBuilder
{
    /**
     * @var TechnicalComponentBuilder
     */
    private $builder;

    public function __construct(TechnicalComponentBuilder $builder)
    {

        $this->builder = $builder;
    }

    /**
     * @param User $user
     * @return PhpDeveloperTestInformation[] array
     * @throws PhpDeveloperTestBuilderException
     * @throws NoExistsNewLevelOfDeveloper
     */
    public function build(User $user): array
    {
        $returnArray = [];

        $allTestsByLevel = $this->getAllTestsByLevel($user);
        $passedIds = $this->getTestPassedIds($user);
        foreach ($allTestsByLevel as $developerLevelTest) {
            $isPassed = isset($passedIds[$developerLevelTest->getId()]);
            $returnArray[] = new PhpDeveloperTestInformation(
                $developerLevelTest,
                $isPassed,
                $this->builder->build($user, $developerLevelTest)
            );

        }
        return $returnArray;
    }

    /**
     * @param User $user
     * @return \App\Entity\PhpDeveloperLevelTest[]|array|\Doctrine\Common\Collections\Collection
     * @throws NoExistsNewLevelOfDeveloper
     */
    private function getAllTestsByLevel(User $user)
    {
        $phpDeveloperRelation = $user->getPhpDeveloperLevelRelation();
        $allTestsByLevel = [];
        if ($phpDeveloperRelation) {
            $phpLevel = $phpDeveloperRelation->getPhpDeveloperLevel();
            if ($phpLevel) {
                $nextLevel = $phpLevel->getNextLevel();
                if ($nextLevel !== null) {
                    $allTestsByLevel = $phpLevel->getPhpDeveloperLevelTests();
                } else {
                    throw new NoExistsNewLevelOfDeveloper('No exists next level');
                }
            }
        }
        return $allTestsByLevel;
    }

    /**
     * @param User $user
     * @return array
     */
    private function getTestPassedIds(User $user): array
    {
        $passedIds = [];
        $passedTests = $user->getPhpDeveloperLevelTestsPassed();
        foreach ($passedTests as $passedTest) {
            $phpDeveloperTest = $passedTest->getPhpDeveloperLevelTest();
            if ($phpDeveloperTest) {
                $id = $phpDeveloperTest->getId();
                $passedIds[$id] = $id;
            }
        }
        return $passedIds;
    }
}
