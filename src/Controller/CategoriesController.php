<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\ProductsRepository; // Assurez-vous d'importer la classe ProductsRepository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{id}', name: 'list')]
    public function list(Categories $category, ProductsRepository $productsRepository): Response
    {
        // $products = $productsRepository->findBy(['categories' => $category]);
        $products = $category->getProducts();
        return $this->render('categories/list.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
