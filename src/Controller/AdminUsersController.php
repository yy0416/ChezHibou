<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users')]
    public function index(UsersRepository $usersRepository): Response
    {
        $users =  $usersRepository->findBy([], ['firstname' => 'asc']);
        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
