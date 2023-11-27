<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $authenticationUtils->getUser();

        if ($user) {
            $lastname = $user->getLastname();
            $firstname = $user->getFirstname();
            $email = $user->getEmail();

            return $this->render('user/index.html.twig', [
                'lastname' => $lastname,
                'firstname' => $firstname,
                'email' => $email
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
