<?php

namespace App\Controller\Api;

use App\Entity\Chantier;
use App\Service\ChantierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\Chantier\Input\CreateChantierInput;
use App\Dto\Chantier\Input\UpsertChantierPostesInput;
use App\Dto\Chantier\Input\UpsertChantierEtapesInput;
use App\Exception\ApiValidationException;
use App\Service\Api\ValidationErrorMapper;
use App\Service\ChantierWriteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/chantiers', name: 'api_v1_chantiers_')]
class ChantierController extends AbstractController
{
    public function __construct(
        private readonly ChantierService $chantierService,
        private readonly ChantierWriteService $chantierWriteService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly ValidationErrorMapper $errorMapper,
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->chantierService->list());
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var CreateChantierInput $in */
        $in = $this->serializer->deserialize($request->getContent(), CreateChantierInput::class, 'json');

        $violations = $this->validator->validate($in);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors($this->errorMapper->map($violations));
        }

        $id = $this->chantierWriteService->createChantier($in);

        return $this->json(['id' => $id], 201);
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


    #[Route('/{id<\d+>}/postes', name: 'upsert_postes', methods: ['POST'])]
    public function upsertPostes(Chantier $chantier, Request $request): JsonResponse
    {
        /** @var UpsertChantierPostesInput $in */
        $in = $this->serializer->deserialize($request->getContent(), UpsertChantierPostesInput::class, 'json');

        $violations = $this->validator->validate($in);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors($this->errorMapper->map($violations));
        }

        $count = $this->chantierWriteService->upsertPostes($chantier, $in);

        return $this->json(['count' => $count], 200);
    }

    #[Route('/{id<\d+>}/etapes', name: 'upsert_etapes', methods: ['POST'])]
    public function upsertEtapes(Chantier $chantier, Request $request): JsonResponse
    {
        /** @var UpsertChantierEtapesInput $in */
        $in = $this->serializer->deserialize($request->getContent(), UpsertChantierEtapesInput::class, 'json');

        $violations = $this->validator->validate($in);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors($this->errorMapper->map($violations));
        }

        $count = $this->chantierWriteService->upsertEtapes($chantier, $in);

        return $this->json(['count' => $count], 200);
    }
}