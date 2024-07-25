<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories =  $categoriesRepository->findBy([], ['CategoryOrder' => 'asc']);
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
