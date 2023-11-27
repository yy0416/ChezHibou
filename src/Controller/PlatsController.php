<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatsController extends AbstractController
{
    #[Route('/plats', name: 'app_plats')]
    public function index(): Response
    {
        return $this->render('plats/index.html.twig', [
            'controller_name' => 'PlatsController',
        ]);
    }
}
