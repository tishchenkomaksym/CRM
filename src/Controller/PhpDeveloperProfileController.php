<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PhpDeveloperLevelTestPassedRepository;
use App\Service\UserInformationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhpDeveloperProfileController extends AbstractController
{
    /**
     * @Route("/php/developer/profile", name="php_developer_profile")
     * @param UserInformationService $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserInformationService $service)
    {
        $user = $this->getUser();
        $manager = $service->getPhpDeveloperManager($user);
        return $this->render(
            'php_developer_profile/index.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'developerManager' => $manager
            ]
        );
    }

    /**
     * @Route("/php/developer/profile/salary-raise", name="php_developer_salary_raise")
     * @param UserInformationService $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function salaryRaise(
        UserInformationService $service,
        PhpDeveloperLevelTestPassedRepository $testPassedRepository
    ): \Symfony\Component\HttpFoundation\Response {
        $user = $this->getUser();
        $tests = $service->getPhpUserLevelTests($this->getUser());
        $passedTestsIds = [];
        if ($user instanceof User) {
            $passedTests = $user->getPhpDeveloperLevelTestsPassed();
            foreach ($passedTests as $passedTest) {
                $testObject = $passedTest->getPhpDeveloperLevelTest();
                if ($testObject) {
                    $id = $testObject->getId();
                    $passedTestsIds[$id] = $id;
                }
            }
            $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel()->getPhpDeveloperLevelTests(
            )[0]->getPhpDeveloperLevelTestPasseds();
            $user->getPhpDeveloperLevelTestsPassed();
        }

        return $this->render(
            'php_developer_profile/salaryRaise.html.twig',
            [
                'passedTestsIds' => $passedTestsIds,
                'tests' => $tests
            ]
        );
    }


}
