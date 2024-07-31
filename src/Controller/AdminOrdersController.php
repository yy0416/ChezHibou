<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrdersFormType;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrdersController extends AbstractController
{
    #[Route('/admin/orders', name: 'app_admin_orders')]
    public function index(Request $request, OrdersRepository $ordersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm((OrdersFormType::class));

        $form->handleRequest($request);

        $startDate = $form->get('start_date')->getData();
        $endDate = $form->get('end_date')->getData();


        $orders = $ordersRepository->findByDateRange($startDate, $endDate);

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders,
            'ordersForm' => $form->createView(),
        ]);
    }


    #[Route('admin/orders/{id}', name: 'app_admin_order_details')]
    public function showOrderDetails(Orders $order): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/orders/details.html.twig', ['order' => $order,]);
    }
}
