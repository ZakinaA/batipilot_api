<?php

namespace App\Controller\Api;

use App\Service\ReferentielsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/referentiels', name: 'api_v1_referentiels_')]
class ReferentielsController extends AbstractController
{
    public function __construct(private readonly ReferentielsService $service) {}

    #[Route('', name: 'get', methods: ['GET'])]
    public function get(): JsonResponse
    {
        return $this->json($this->service->getAll());
    }
}