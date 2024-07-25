<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
{
    #[Route('/admin/products', name: 'app_admin_products')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/admin/products/edition/{id}', name: 'app_admin_products_edit')]

    public function edit(ProductsRepository $productsRepository): Response
    {
        return $this->render('admin/products/edit.html.twig');
    }
}
