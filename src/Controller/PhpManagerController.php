<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PhpDeveloperTest\Exception\NoExistsNewLevelOfDeveloper;
use App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException;
use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\BaseEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\ProjectEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime\UserToProjectTimeSpendBuilder;
use App\Service\UserInformationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @param User $user
     * @param PhpDeveloperTestsInformationBuilder $builder
     * @param BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder
     * @param ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder
     * @param UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
     * @return Response
     * @throws NoExistsNewLevelOfDeveloper
     * @throws PhpDeveloperTestBuilderException
     * @throws NoRequiredHoursException
     */
    public function makeRise(
        User $user,
        PhpDeveloperTestsInformationBuilder $builder,
        BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder,
        ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder,
        UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
    )
    {
        $userProjects = $projectTimeSpendBuilder->build($user);
        $tests = $builder->build($user);
        return $this->render(
            'php_manager/make_rise.html.twig',
            [
                'tests' => $tests,
                'effectiveTime' => $baseEffectiveTimeBuilder->build($user),
                'projectTime' => $projectEffectiveTimeBuilder->build($user, $userProjects),
                'projects' => $userProjects,
            ]
        );
    }
}
