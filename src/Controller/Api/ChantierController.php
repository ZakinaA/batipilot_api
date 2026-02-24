<?php

namespace App\Controller\Api;

use App\Entity\Chantier;
use App\Service\ChantierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/chantiers', name: 'api_v1_chantiers_')]
class ChantierController extends AbstractController
{
    public function __construct(
        private readonly ChantierService $chantierService
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->chantierService->list());
    }

    #[Route('/{id<\d+>}/overview', name: 'overview', methods: ['GET'])]
    public function overview(Chantier $chantier): JsonResponse
    {
        return $this->json($this->chantierService->showOverview($chantier));
    }

    #[Route('/{id<\d+>}/kpi', name: 'kpi', methods: ['GET'])]
    public function kpi(Chantier $chantier): JsonResponse
    {
        return $this->json($this->chantierService->showKpi($chantier));
    }

    #[Route('/{id<\d+>}/suivi', name: 'suivi', methods: ['GET'])]
    public function suivi(Chantier $chantier): JsonResponse
    {
        return $this->json($this->chantierService->showSuivi($chantier));
    }
}