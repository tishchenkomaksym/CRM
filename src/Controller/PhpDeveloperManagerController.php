<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhpDeveloperManagerController extends AbstractController
{
    /**
     * @
     * @Route("/php/developer/manager", name="php_developer_manager")
     */
    public function index()
    {
        return $this->render('php_developer_manager/index.html.twig', [
            'controller_name' => 'PhpDeveloperManagerController',
        ]);
    }
}
