<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:56 AM
 */

namespace App\Service;

use App\Data\Sdt\SdtCollection;
use App\Entity\PhpDeveloperLevelTest;
use App\Entity\PhpDeveloperLevelTestPassed;
use App\Entity\User;
use App\Repository\SdtRepository;

class UserInformationService
{
    /**
     * UserInformationService constructor.
     * @param SdtRepository $sdtRepository
     * @param int $userId
     */
    public function __construct()
    {

    }

    public function getAllUserSdt(SdtRepository $sdtRepository, int $userId): SdtCollection
    {
        return new SdtCollection($sdtRepository->findBy(['user' => $userId]));
    }

    /**
     * @param SdtCollection $sdtCollection
     * @param User $user
     * @return int
     */
    public function getSdtLeft(SdtCollection $sdtCollection, User $user): int
    {
        $existSDT = 0;
        foreach ($user->getMonthlySdts() as $monthlySdt) {
            $existSDT += $monthlySdt->getCount();
        }
        return $existSDT - $sdtCollection->getCountSum();
    }

    public function getPhpUserLevel(User $user): ?string
    {
        if ($user->getPhpDeveloperLevelRelation() === null || $user->getPhpDeveloperLevelRelation(
            )->getPhpDeveloperLevel() === null) {
            return 'Undefined';
        }
        return $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel()->getTitle();
    }

    public function getPhpUserLevelTests(User $user): array
    {
        if ($user->getPhpDeveloperLevelRelation() === null || $user->getPhpDeveloperLevelRelation(
            )->getPhpDeveloperLevel() === null) {
            return [];
        }
        return $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel()->getPhpDeveloperLevelTests()->getValues();
    }

    /**
     * @param User $user
     * @return User[]
     */
    public function getPhpManagerDevelopers(User $user): array
    {
        $returnValue = [];
        foreach ($user->getPhpManagerDeveloperRelations() as $relation) {
            $returnValue[] = $relation->getPhpDeveloper();
        }

        return $returnValue;
    }

    /**
     * @param User $user
     * @return User
     */
    public function getPhpDeveloperManager(User $user): ?User
    {
        $manager = null;
        foreach ($user->getPhpDeveloperManagerRelations() as $relation) {
            $manager = $relation->getManager();
        }
        return $manager;
    }

    public static function getPhpDeveloperNotPassedTests(User $user)
    {
        /** @var PhpDeveloperLevelTestPassed[] $passedTests */
        $passedTests = $user->getPhpDeveloperLevelTestsPassed()->getValues();
        /** @var PhpDeveloperLevelTest[] $allTestsOfUserLevel */
        $allTestsOfUserLevel = $user
            ->getPhpDeveloperLevelRelation()
            ->getPhpDeveloperLevel()
            ->getPhpDeveloperLevelTests()->getValues();
        $result = [];

        foreach ($allTestsOfUserLevel as $allTest) {
            $add = true;
            foreach ($passedTests as $passedTest) {
                if ($passedTest->getPhpDeveloperLevelTest()->getId()=== $allTest->getId()) {
                    $add = false;
                    break;
                }
            }
            if($add) {
                $result[] = $allTest;
            }
        }
        return $result;
    }
}
