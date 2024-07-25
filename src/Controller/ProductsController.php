<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductsController extends AbstractController
{
    private $entityManager;
    private $pictureService;

    public function __construct(EntityManagerInterface $entityManager, PictureService $pictureService)
    {
        $this->entityManager = $entityManager;
        $this->pictureService = $pictureService;
    }


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

    #[Route('products/ajout', name: 'app_products_ajout')]

    public function add(Request $request): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
