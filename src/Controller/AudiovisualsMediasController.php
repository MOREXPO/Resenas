<?php

namespace App\Controller;

use App\Repository\AudiovisualRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AudiovisualsMediasController extends AbstractController
{
    public function __construct(
        private AudiovisualRepository $audiovisualRepository,
    ) {
    }
    public function __invoke($page,$orderBy): JsonResponse
    {
        $audiovisuals = $this->audiovisualRepository->findByAverageRating($page,30,$orderBy);
        return new JsonResponse([
            "hydra:member" => $audiovisuals,
        ]);
    }
}