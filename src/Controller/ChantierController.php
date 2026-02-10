<?php
namespace App\Controller;

use App\Repository\ChantierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ChantierController extends AbstractController
{
    #[Route('/chantiers/type', name: 'chantiers_type', methods: ['GET'])]
    public function list(ChantierRepository $chantierRepo): JsonResponse
    {
        $result = $chantierRepo->findChantiersDashboard();

        $formatChantiers = fn(array $chantiers): array => array_map(
            fn($c) => [
                'id' => $c->getId(),
                'ville' => $c->getVille(),
                'clientNom' => $c->getClient()?->getNom(),
                'clientPrenom' => $c->getClient()?->getPrenom(),
                'dateDemarrage' => $c->getDateDemarrage()?->format('Y-m-d'),
                'dateDebutPrevue' => $c->getDateDebutPrevue()?->format('Y-m-d'),
                'dateReception' => $c->getDateReception()?->format('Y-m-d'),
        ],
        $chantiers
        );

        return $this->json([
            'demarres' => $formatChantiers($result['demarres'], 'demarres'),
            'aVenir' => $formatChantiers($result['aVenir'], 'aVenir'),
            'termines' => $formatChantiers($result['termines'], 'termines'),
        ]);
    }
}
