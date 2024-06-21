<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }
    public function __invoke(Request $request): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        // Crear una nueva instancia de la entidad User

        if (array_key_exists('email', $params)) {
            $user = new User();
            $user->setEmail($params['email']);
            if (array_key_exists('password', $params)) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $params['password']
                );
                $user->setPassword($hashedPassword);
                // Guardar el usuario en la base de datos
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } else
                throw new Exception('Falta la contraseña');
        } else
            throw new Exception('Falta el email');

        return new JsonResponse(["email" => $user->getEmail(), "roles" => $user->getRoles()]);
    }
}