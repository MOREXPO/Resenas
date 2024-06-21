<?php

namespace App\Controller;

use App\Entity\Audiovisual;
use App\Repository\AudiovisualRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class addAudiovisualController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private AudiovisualRepository $audiovisualRepository,
    ) {
    }
    public function __invoke($id): JsonResponse
    {
        $audiovisual = $this->audiovisualRepository->find($id);
        $userAudiovisuals = $this->getUser()->getAudiovisuals()->map(function ($audiovisual) {
            return $audiovisual->getId();
        })->toArray();
        if (
            in_array($audiovisual->getId(), $userAudiovisuals)
        ) {
            $this->getUser()->removeAudiovisual($audiovisual);
        } else {
            $this->getUser()->addAudiovisual($audiovisual);
        }
        $this->userRepository->save($this->getUser(), true);
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