<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{id}', name: 'list', methods: ['GET'])]
    public function list(Categories $categories): Response
    {
        $products = $categories->getProducts();

        return $this->render('categories/list.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    #[Route('/{id}/json', name: 'list_json', methods: ['GET'])]
    public function listJson(Categories $category): JsonResponse
    {
        $products = $category->getProducts();
        $productsArray = [];

        foreach ($products as $product) {
            $productsArray[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'image' => $product->getImages()[0] ?? '', // assuming the first image is used
            ];
        }

        return new JsonResponse($productsArray);
    }
}
