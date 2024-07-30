<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('message', 'Votre panier est vide.');
            return $this->redirectToRoute('app_products');
        }

        // Créer la commande
        $order = new Orders();
        $order->setUsers($this->getUser());
        $order->setReference(uniqid());
        $order->setCreatedAt(new \DateTimeImmutable());

        $totalPrice = 0;

        // Parcourir le panier pour créer les détails de commande
        foreach ($panier as $itemId => $quantity) {
            $product = $productsRepository->find($itemId);

            if (!$product) {
                $this->addFlash('message', 'Produit non trouvé dans le panier.');
                continue;
            }

            $price = $product->getPrice();
            $totalPrice += $price * $quantity;

            $orderDetails = new OrdersDetails();
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }

        // Vérifier si des détails de commande ont été ajoutés
        if (count($order->getOrdersDetails()) === 0) {
            $this->addFlash('message', 'Aucun produit valide dans le panier.');
            return $this->redirectToRoute('app_products');
        }

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('app_orders_confirm', ['id' => $order->getId()]);
    }

    #[Route('/confirm/{id}', name: 'confirm')]
    public function confirm(int $id, OrdersRepository $ordersRepository): Response
    {
        $order = $ordersRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        $totalPrice = 0;
        foreach ($order->getOrdersDetails() as $detail) {
            $totalPrice += $detail->getPrice() * $detail->getQuantity();
        }

        return $this->render('orders/confirm.html.twig', [
            'order' => $order,
            'totalPrice' => $totalPrice
        ]);
    }

    #[Route('/', name: 'index')]
    public function index(OrdersRepository $ordersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $orders = $ordersRepository->findBy(['users' => $user]);

        return $this->render('orders/index.html.twig', ['orders' => $orders]);
    }

    #[Route('/details/ajax/{id}', name: 'details_ajax')]
    public function detailsAjax(int $id, OrdersRepository $ordersRepository): Response
    {
        $order = $ordersRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        return $this->render('orders/_details.html.twig', ['order' => $order]);
    }
}
