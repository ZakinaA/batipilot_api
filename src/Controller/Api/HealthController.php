<?php
namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HealthController extends AbstractController
{
    #[Route('/api/v1/health', name: 'app_health', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        try {
            $doctrine->getConnection()->executeQuery('SELECT 1');

            return $this->json([
                'status' => 'ok',
                'database' => 'ok',
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'database' => 'error',
            ], 503);
        }

       
    }
}