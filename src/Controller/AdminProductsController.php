<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class AdminProductsController extends AbstractController
{

    private $entityManager;
    private $pictureService;

    public function __construct(EntityManagerInterface $entityManager, PictureService $pictureService)
    {
        $this->entityManager = $entityManager;
        $this->pictureService = $pictureService;
    }

    #[Route('/admin/products', name: 'app_admin_products')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('products/ajout', name: 'app_products_ajout')]

    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product =  new Products();

        //créé formulaire

        $productForm = $this->createForm(ProductsFormType::class, $product);
        // Handle form request

        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            //On recupere les images
            $images = $productForm->get('images')->getData();

            $imagesPath = [];

            foreach ($images as $image) {
                //On definit le dossier de destination
                $folder = 'products';

                //On appelle le service d'ajout
                $fichier = $this->pictureService->add($image, $folder, 300, 300);

                $imagesPath[] = $fichier;
            }
            $product->setImages($imagesPath);
            $product->setCreatedAt(new \DateTimeImmutable());
            //Save product to the database
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $this->addFlash('success', 'Produit ajouté avec succès');

            //Redirect to the product list page or details page
            return $this->redirectToRoute('app_products');
        }
        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }

    #[Route('/admin/products/edition/{id}', name: 'app_admin_products_edit')]

    public function edit(Request $request, Products $product): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $productForm = $this->createForm(ProductsFormType::class, $product);
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            $images = $productForm->get('images')->getData();
            $imagesPath = [];

            foreach ($images as $image) {
                $folder = 'products';
                $fichier =  $this->pictureService->add($image, $folder, 300, 300);
                $imagesPath[] = $fichier;
            }

            $product->setImages($imagesPath);
            //$product->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->flush();
            $this->addFlash('success', 'Produit mis à jour avec succès');

            return $this->redirectToRoute('app_admin_products');
        }


        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView(),
            'product' => $product,
        ]);
    }


    #[Route('/admin/products/delete/{id}', name: 'app_admin_products_delete')]

    public function delete(Products $product): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($product);
        $this->entityManager->flush();
        $this->addFlash('success', 'Produit supprimé avec succès');

        return $this->redirectToRoute('app_admin_products');
    }
}
