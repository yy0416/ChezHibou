<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductsController extends AbstractController
{



    #[Route('products', name: 'app_products')]
    public function index(ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        $products = $productsRepository->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }



    #[Route('/products/{name}', name: 'app_products_details')]
    public function details(string $name, ProductsRepository $productsRepository): Response
    {
        $product = $productsRepository->findOneBy(['name' => $name]);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        return $this->render('products/details.html.twig', [
            'product' => $product
        ]);
    }


    #[Route('/product/{id}/images', name: 'app_product_images')]
    public function showImages(int $id, ProductsRepository $productsRepository): Response
    {
        $product = $productsRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/images.html.twig', [
            'product' => $product,
        ]);
    }
}
