<?php

namespace App\Controller\Api;

use App\Entity\Chantier;
use App\Service\ChantierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api2/chantiers')]
class ChantierController extends AbstractController
{
    public function __construct(
        private readonly ChantierService $chantierService
    ) {}

    #[Route('/list', name: 'chantiers_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->chantierService->list());
    }

    #[Route('/show_overview/{id}', name: 'chantier_show_overview', methods: ['GET'])]
    public function showOverview(Chantier $chantier): JsonResponse
    {
        return $this->json($this->chantierService->showOverview($chantier));
    }

    #[Route('/show_kpi/{id}', name: 'chantier_show_kpi', methods: ['GET'])]
    public function showKpi(Chantier $chantier): JsonResponse
    {
        return $this->json($this->chantierService->showKpi($chantier));
    }

    #[Route('/show_suivi/{id}', name: 'chantier_suivi', methods: ['GET'])]
    public function showSuivi(Chantier $chantier): JsonResponse
    {
        return $this->json($this->chantierService->showSuivi($chantier));
    }
}