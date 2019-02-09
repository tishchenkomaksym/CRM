<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserInformationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhpManagerController extends AbstractController
{
    /**
     * @Route("/php/manager", name="php_manager")
     */
    public function index(UserInformationService $userInformationService)
    {
        $user = $this->getUser();
        $managerDevelopers = $userInformationService->getPhpManagerDevelopers($user);
        return $this->render(
            'php_manager/index.html.twig',
            [
                'subordinates' => $managerDevelopers
            ]
        );
    }

    /**
     * @Route("/php/manager/make/rise/{id}", name="php_manager_make_rise")
     */
    public function makeRise(User $user, UserInformationService $userInformationService)
    {
        $notPassedTests = UserInformationService::getPhpDeveloperNotPassedTests($user);
        $this->render('php_manager/make_rise.html.twig', ['notPassedTests' => $notPassedTests]);
    }
}
