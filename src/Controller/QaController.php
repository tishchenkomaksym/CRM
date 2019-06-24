<?php

namespace App\Controller;

use App\Service\QaAgent\Builder\QaTechnicalSkillDTOBuilder;
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
     * @Route("/qa/view/technical/skills", name="qa_view_technical_skills")
     * @param UserInformationService $service
     * @param QaTechnicalSkillDTOBuilder $technicalSkillsBuilder
     * @return Response
     * @throws Exception
     */
    public function technicalSkills(
        UserInformationService $service,
        QaTechnicalSkillDTOBuilder $technicalSkillsBuilder
    ): Response
    {
        $user = $this->getUser();
        $qaManager = $service->getQaManager($user);
        $skillTests = $technicalSkillsBuilder->getResult($user);
        return $this->render(
            'qa_view/technicalSkills.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'qaManagers' => $qaManager,
                'skillRows' => $skillTests->getSkillRows(),
                'jiraHours' => $skillTests->getJiraHours(),
            ]
        );
    }
    /**
     * @Route("/qa/view/theoretical/skills", name="qa_view_theoretical_skills")
     * @param UserInformationService $service
     * @return Response
     * @throws Exception
     */
    public function theoreticalSkills(
        UserInformationService $service
    )
    {
        $user = $this->getUser();
        $qaManager = $service->getQaManager($user);
        return $this->render(
            'qa_view/theoreticalSkills.html.twig',
            [
                'userLevel' => $service->getPhpUserLevel($user),
                'qaManagers' => $qaManager
            ]
        );
    }
}
