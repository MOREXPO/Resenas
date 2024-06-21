<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/registration', name: 'app_api_login')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiLoginController.php',
        ]);
    }

    #[Route('/auth', name: 'auth', methods: ['POST'])]
    public function login(#[CurrentUser] $user = null): Response
    {
        return $this->json([
            'user' => $user ? $user->getId() : null,
            "email" => $user ? $user->getEmail() : null,
            "roles" => $user ? $user->getRoles() : null
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
