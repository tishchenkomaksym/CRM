<?php

namespace App\Controller;

use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\BaseEffectiveTimeBuilder;
use App\Service\UserInformationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_PHP_DEVELOPER")
 * Class PhpDeveloperProfileController
 * @package App\Controller
 */
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
                'developerManagers' => $manager
            ]
        );
    }

    /**
     * @Route("/php/developer/profile/salary-raise", name="php_developer_salary_raise")
     * @param PhpDeveloperTestsInformationBuilder $builder
     * @param BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Service\PhpDeveloperTest\Exception\NoExistsNewLevelOfDeveloper
     * @throws \App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException
     * @throws \App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException
     */
    public function salaryRaise(
        PhpDeveloperTestsInformationBuilder $builder,
        BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder
    ): \Symfony\Component\HttpFoundation\Response {
        return $this->render(
            'php_developer_profile/salaryRaise.html.twig',
            [
                'tests' => $builder->build($this->getUser()),
                'effectiveTime' => $baseEffectiveTimeBuilder->build($this->getUser()),
                'projectTime' => [],
            ]
        );
    }


}
