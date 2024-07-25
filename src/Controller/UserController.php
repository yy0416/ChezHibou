<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($user) {
            if ($user instanceof \App\Entity\Users) {
                $lastname = $user->getLastname();
                $firstname = $user->getFirstname();
                $email = $user->getEmail();
                $address = $user->getAddress();
                $city = $user->getCity();

                return $this->render('user/index.html.twig', [
                    'lastname' => $lastname,
                    'firstname' => $firstname,
                    'email' => $email,
                    'address' => $address,
                    'city' => $city,
                ]);
            } else {
                throw new \Exception('User is not of the expected class');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('user/profile', name: 'app_user_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $profileForm = $this->createForm(ProfileFormType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('Success', 'Votre profil a été mis à jour');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/profileEdit.html.twig', [
            'profileForm' => $profileForm->createView(),
        ]);
    }
}
