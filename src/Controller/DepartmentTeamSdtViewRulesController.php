<?php

namespace App\Controller;

use App\Entity\DepartmentTeamSdtViewRules;
use App\Form\DepartmentTeamSdtViewRulesType;
use App\Repository\DepartmentTeamSdtViewRulesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/department/team/sdt/view/rules")
 */
class DepartmentTeamSdtViewRulesController extends AbstractController
{
    /**
     * @Route("/", name="rules_index", methods={"GET"})
     */
    public function index(DepartmentTeamSdtViewRulesRepository $rulesRepository)
    {

        return $this->render(
            'department_team_sdt_view_rules/index.html.twig',
            [
                'rules' => $rulesRepository->findAll(),
            ]
        );
    }
    /**
     * @Route("/", name="rules_new", methods={"GET"})
     */
    public function new(Request $request): Response
    {
        $departmentTeamSdtViewRules = new DepartmentTeamSdtViewRules();
        $form = $this->createForm(DepartmentTeamSdtViewRulesType::class, $departmentTeamSdtViewRules);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departmentTeamSdtViewRules->setDepartment(strtolower($form->get('department')->getData()));
            $departmentTeamSdtViewRules->setTeam(strtolower($form->get('team')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($departmentTeamSdtViewRules);
            $entityManager->flush();

            return $this->redirectToRoute('department_team_sdt_view_rules_index');
        }

        return $this->render(
            'department_team_sdt_view_rules/new.html.twig',
            [

            ]
        );
    }
}
