<?php

namespace App\Controller;

use App\Service\HolidayService;
use App\UserDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/default", name="default")
     */
    public function index(UserDataProvider $userDataProvider, HolidayService $holidayService)
    {
//        $time = $userDataProvider->getUserTime('ivan.melnichuk', '01/01/2019', '31/01/2019');
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
