<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_PHP_MANAGER")
 * Class PhpDeveloperSalaryReportController
 * @package App\Controller
 */
class PhpDeveloperSalaryReportController extends AbstractController
{
    /**
     * @Route("/php/developer/salary/report", name="php_developer_salary_report")
     */
    public function index()
    {

        return $this->render('php_developer_salary_report/index.html.twig', [
            'controller_name' => 'PhpDeveloperSalaryReportController',
        ]);
    }
}
