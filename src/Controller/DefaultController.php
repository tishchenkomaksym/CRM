<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     *
     * @Route("/default", name="default")
     * @param ObjectManager $objectManager
     * @return Response
     */
    public function index(ObjectManager $objectManager): Response
    {
        $objectManager->flush();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
