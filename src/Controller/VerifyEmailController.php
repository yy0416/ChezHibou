<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyEmailController extends AbstractController
{
    private $verifyEmailHelper;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper, EntityManagerInterface $entityManager)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->entityManager = $entityManager;
    }

    #[Route('/verify-email/{id}/{hash}', name: 'app_verify_email', methods: ['GET'])]
    public function verify(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, $user->getId(), $user->getEmail());

            // Set the user as verified
            $user->setIsVerified(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your email address has been verified.');

            return $this->redirectToRoute('app_login');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }
    }
}
