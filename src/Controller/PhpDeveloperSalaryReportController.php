<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
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
     * @throws \Exception
     */
    public function index(UserRepository $userRepository, ReportWorkHoursBuilderDecorator $builderDecorator)
    {
        $users = $userRepository->findAll();
        $items = [];
        foreach ($users as $user) {
            $items = $builderDecorator->build($user);
        }

        return $this->render('php_developer_salary_report/index.html.twig', [
            'items' => $items
        ]);
    }
}
