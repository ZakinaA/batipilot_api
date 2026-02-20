<?php

namespace App\Controller\Api;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierMiniOutput;
use App\Dto\Chantier\ChantierDetailOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Dto\Chantier\ChantierPosteOutput;
use App\Dto\Chantier\ChantierPosteKpiOutput;
use App\Dto\Chantier\ChantierKpiOutput;
use App\Dto\Chantier\ChantierEtapeOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ChantierService;

#[Route('/api2/chantiers')]
class ChantierController extends AbstractController
{
    public function __construct(
        private readonly ChantierRepository $repository,
        private readonly ChantierService $chantierService 
    ) {}

    #[Route('/list', name: 'chantiers_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->chantierService->list()
        );
    
    }

    #[Route('/show_overview/{id}', name: 'chantier_show_overview', methods: ['GET'])]
    public function showOverview(int $id): JsonResponse
    {
        $chantier = $this->repository->find($id);
        if (!$chantier) {
            return $this->json(['error' => 'Chantier non trouvé'], 404);
        }
        else
        {
            return $this->json(
                $this->chantierService->showOverview($chantier)
            ); 
        }
        return $this->json($dto);
    }

    
    #[Route('/show_kpi/{id}', name: 'chantier_show', methods: ['GET'])]
    public function showKpi(int $id): JsonResponse
    {
        $chantier = $this->repository->find($id);
        if (!$chantier) {
            return $this->json(['error' => 'Chantier non trouvé'], 404);
        }
        else
        {
            return $this->json(
                $this->chantierService->showKpi($chantier)
            ); 
        }
        return $this->json($dto);
    }

     #[Route('/show_suivi/{id}', name: 'chantier_suivi', methods: ['GET'])]
    public function showEtapes(int $id): JsonResponse
    {
        $chantier = $this->repository->find($id);
        if (!$chantier) {
            return $this->json(['error' => 'Chantier non trouvé'], 404);
        }
        else
        {
            return $this->json(
                $this->chantierService->showSuivi($chantier)
            ); 
        }
        return $this->json($dto);
    }


}
