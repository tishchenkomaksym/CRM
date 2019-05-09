<?php

namespace App\Controller;

use App\Service\PhpDeveloperTest\Exception\NoExistsNewLevelOfDeveloper;
use App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException;
use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\BaseEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\ProjectEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime\UserToProjectTimeSpendBuilder;
use App\Service\User\Sdt\LeftSdtCalculator;
use App\Service\UserInformationService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
     * @return Response
     * @throws Exception
     */
    public function index(
        UserInformationService $service,
        LeftSdtCalculator $leftSdtCalculator,
        ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
    )
    {
        $user = $this->getUser();
        $manager = $service->getPhpDeveloperManager($user);
        $leftSdt = $leftSdtCalculator->calculate($this->getUser());

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
     * @return Response
     * @throws NoExistsNewLevelOfDeveloper
     * @throws PhpDeveloperTestBuilderException
     * @throws NoRequiredHoursException
     * @throws Exception
     */
    public function salaryRaise(
        PhpDeveloperTestsInformationBuilder $builder,
        BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder,
        ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder,
        UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
    ): Response
    {
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

    /**
     * @param PhpDeveloperTestsInformationBuilder $builder
     * @param BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder
     * @param ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder
     * @param UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
     * @return Response
     * @throws NoExistsNewLevelOfDeveloper
     * @throws NoRequiredHoursException
     * @throws PhpDeveloperTestBuilderException
     */
    public function qaSalaryRaise(
        PhpDeveloperTestsInformationBuilder $builder,
        BaseEffectiveTimeBuilder $baseEffectiveTimeBuilder,
        ProjectEffectiveTimeBuilder $projectEffectiveTimeBuilder,
        UserToProjectTimeSpendBuilder $projectTimeSpendBuilder
    ): Response
    {
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
