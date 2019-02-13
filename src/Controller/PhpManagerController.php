<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\UserInformationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhpManagerController
 * @IsGranted("ROLE_PHP_MANAGER")
 * @package App\Controller
 */
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
    public function makeRise(User $user)
    {
        $tests = PhpDeveloperTestsInformationBuilder::build($user);
        return $this->render('php_manager/make_rise.html.twig', ['tests' => $tests]);
    }
}
