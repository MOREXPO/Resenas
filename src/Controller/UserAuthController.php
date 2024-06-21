<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserAuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            "id" => $this->getUser()->getId(),
            "email" => $this->getUser()->getEmail(),
            "roles" => $this->getUser()->getRoles(),
            "audiovisuals" => $this->getUser()->getAudiovisuals()->map(function ($audiovisual) {
                return '/api/audiovisuals/' . $audiovisual->getId();
            })->toArray()
        ]);
    }
}