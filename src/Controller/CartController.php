<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $panier = $session->get('panier', []);
        // On initialise des variables
        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productsRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total
        ]);
    }



    #[Route('/add/{id}', name: 'add')]
    public function add(Products $product, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On ajoute le produit dans le panier s'il n'y est pas encore
        //Sinon on incrément sa quantité
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);
        // On rediger vers la page de panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Products $product, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On retire le produit du le panier s'il n'y a qu'un exemplaire
        //Sinon on incrément sa quantité
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);
        // On rediger vers la page de panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Products $product, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $product->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);


        if (!empty($panier[$id])) {

            unset($panier[$id]);
        }


        $session->set('panier', $panier);
        // On rediger vers la page de panier
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');
        // On rediger vers la page de panier
        return $this->redirectToRoute('cart_index');
    }
}
