<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reservation', name: 'reservation')]
    public function reserveTable(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire a été soumis et est valide, effectuez l'action nécessaire ici.

            // Exemple : Enregistrez la réservation dans la base de données.
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            // Redirigez vers une autre page ou affichez un message de réussite.
            return $this->redirectToRoute('reservation_success');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reservation/success', name: 'reservation_success')]
    public function reservationSuccess(): Response
    {
        return $this->render('reservation/success.html.twig');
    }
}
