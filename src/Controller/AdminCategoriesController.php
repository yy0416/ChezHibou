<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Category;
use App\Form\CategoriesFormType;
use App\Form\CategoryType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories =  $categoriesRepository->findBy([], ['CategoryOrder' => 'asc']);
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categories/add', name: 'app_admin_category_add')]
    public function add(Request $request): Response
    {
        $category =  new Categories();
        $form =  $this->createForm(CategoriesFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_categories');
        }
        return $this->render('admin/categories/add.html.twig', [
            'categoriesForm' => $form->createView()
        ]);
    }

    #[Route('admin/categories/edit/{id}', name: 'app_admin_category_edit')]
    public function edit(Request $request, Categories $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoriesFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'categoriesForm' => $form->createView(),
            'category' => $category,
        ]);
    }
    #[Route('admin/categories/delete/{id}', name: 'app_admin_category_delete')]
    public function delete(Categories $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($category);
        $this->entityManager->flush();
        $this->addFlash('success', 'Catégorie supprimée avec succès');

        return $this->redirectToRoute('app_admin_categories');
    }
}
