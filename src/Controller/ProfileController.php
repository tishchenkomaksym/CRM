<?php

namespace App\Controller;

use App\Repository\SdtRepository;
use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\BaseEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\ProjectEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime\UserToProjectTimeSpendBuilder;
use App\Service\UserInformationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * Class PhpDeveloperProfileController
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
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
            'profile/index.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'developerManagers' => $manager,
                'leftSdt' => $leftSdt,
                'workingHoursInformation' => $workingHoursInformation
            ]
        );
    }

    /**
     * @IsGranted("ROLE_PHP_DEVELOPER")
     * @Route("/profile/salary-raise", name="salary_raise")
     * @param PhpDeveloperTestsInformationBuilder $builder
     * @param BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder
     * @param ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder
     * @param UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Service\PhpDeveloperTest\Exception\NoExistsNewLevelOfDeveloper
     * @throws \App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException
     * @throws \App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException
     */
    public function salaryRaise(
        PhpDeveloperTestsInformationBuilder $builder,
        BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder,
        ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder,
        UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
    ): \Symfony\Component\HttpFoundation\Response {
        $user = $this->getUser();
        $userProjects = $projectTimeSpendBuilder->build($user);
        return $this->render(
            'profile/salaryRaise.html.twig',
            [
                'tests' => $builder->build($user),
                'effectiveTime' => $baseEffectiveTimeBuilder->build($user),
                'projectTime' => $projectEffectiveTimeBuilder->build($user, $userProjects),
                //                'projects' => $userProjects,
                'projects' => [],
            ]
        );
    }


}
