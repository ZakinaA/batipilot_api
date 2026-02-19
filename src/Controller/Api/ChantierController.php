<?php

namespace App\Controller\Api;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierMiniOutput;
use App\Dto\Chantier\ChantierDetailOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Dto\Chantier\ChantierPosteOutput;
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

    
    #[Route('/show/{id}', name: 'chantier_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $chantier = $this->repository->find($id);
        if (!$chantier) {
            return $this->json(['error' => 'Chantier non trouvé'], 404);
        }
        else
        {
            return $this->json(
                $this->chantierService->show($chantier)
            ); 
        }
        return $this->json($dto);
    }
}
