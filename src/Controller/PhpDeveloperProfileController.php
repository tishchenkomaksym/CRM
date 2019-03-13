<?php

namespace App\Controller;

use App\Repository\SdtRepository;
use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
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
     * @param UserInformationService $userInformationService
     * @param SdtRepository $sdtRepository
     * @param ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(
        UserInformationService $service,
        UserInformationService $userInformationService,
        SdtRepository $sdtRepository,
        ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
    ) {
        $user = $this->getUser();
        $manager = $service->getPhpDeveloperManager($user);

        $sdtCollection = $userInformationService->getAllUserSdt($sdtRepository, $this->getUser()->getId());

        $leftSdt = $userInformationService->getSdtLeft($sdtCollection, $this->getUser());

        $workingHoursInformation = $baseWorkHoursInformationBuilder->build($this->getUser());
        return $this->render(
            'php_developer_profile/index.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'developerManagers' => $manager,
                'leftSdt' => $leftSdt,
                'workingHoursInformation' => $workingHoursInformation
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
