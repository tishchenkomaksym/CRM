<?php

namespace App\Controller;

use App\Service\PhpDeveloperTest\Exception\NoExistsNewLevelOfDeveloper;
use App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException;
use App\Service\PhpDeveloperTest\PhpDeveloperTestsInformationBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\BaseEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\NoRequiredHoursException;
use App\Service\User\PhpDeveloperLevel\EffectiveTime\ProjectEffectiveTimeBuilder;
use App\Service\User\PhpDeveloperLevel\ProjectEffectiveTime\UserToProjectTimeSpendBuilder;
use App\Service\UserInformationService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_QA_AGENT")
 * Class QaProfileController
 * @package App\Controller
 */
class QaController extends AbstractController
{
    /**
     * @Route("/qa/view", name="qa_view")
     * @param UserInformationService $service
     * @return Response
     * @throws Exception
     */
    public function index(
        UserInformationService $service
    )
    {
        $user = $this->getUser();
        $qaManager = $service->getQaManager($user);
        return $this->render(
            'qa_view/index.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'qaManagers' => $qaManager
            ]
        );
    }
    /**
     * @Route("/qa/view/tech/skills", name="qa_view_tech_skills")
     * @param UserInformationService $service
     * @return Response
     * @throws Exception
     */
    public function techSkills(
        UserInformationService $service
    )
    {
        $user = $this->getUser();
        $qaManager = $service->getQaManager($user);
        return $this->render(
            'qa_view/techSkills.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'qaManagers' => $qaManager
            ]
        );
    }

    /**
     * @IsGranted("ROLE_QA_AGENT")
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
     * @throws Exception
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
