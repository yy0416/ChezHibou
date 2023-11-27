<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products
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
