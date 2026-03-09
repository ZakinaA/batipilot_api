<?php

namespace App\Controller\Api;

use App\Dto\Referentiels\Input\CreateOrUpdateEtapeInput;
use App\Dto\Referentiels\Input\CreateOrUpdatePosteInput;
use App\Entity\Etape;
use App\Entity\Poste;
use App\Exception\ApiValidationException;
use App\Service\Api\ValidationErrorMapper;
use App\Service\ReferentielsService;
use App\Service\ReferentielsWriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/referentiels', name: 'api_v1_referentiels_')]
class ReferentielsController extends AbstractController
{
    public function __construct(
        private readonly ReferentielsService $service,
        private readonly ReferentielsWriteService $writeService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly ValidationErrorMapper $errorMapper,
    ) {}

    #[Route('', name: 'get', methods: ['GET'])]
    public function get(): JsonResponse
    {
        return $this->json($this->service->getAll());
    }

    #[Route('/postes-avec-etapes', name: 'postes_avec_etapes', methods: ['GET'])]
    public function getPostesAvecEtapes(): JsonResponse
    {
        return $this->json($this->service->getPostesAvecEtapes());
    }

    #[Route('/postes', name: 'poste_create', methods: ['POST'])]
    public function createPoste(Request $request): JsonResponse
    {
        /** @var CreateOrUpdatePosteInput $input */
        $input = $this->serializer->deserialize(
            $request->getContent(),
            CreateOrUpdatePosteInput::class,
            'json'
        );

        $violations = $this->validator->validate($input);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors(
                $this->errorMapper->map($violations)
            );
        }

        $id = $this->writeService->createPoste($input);

        return $this->json(['id' => $id], 201);
    }

    #[Route('/postes/{id<\d+>}', name: 'poste_update', methods: ['PUT'])]
    public function updatePoste(Poste $poste, Request $request): JsonResponse
    {
        /** @var CreateOrUpdatePosteInput $input */
        $input = $this->serializer->deserialize(
            $request->getContent(),
            CreateOrUpdatePosteInput::class,
            'json'
        );

        $violations = $this->validator->validate($input);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors(
                $this->errorMapper->map($violations)
            );
        }

        $this->writeService->updatePoste($poste, $input);

        return $this->json(null, 204);
    }

    #[Route('/etapes', name: 'etape_create', methods: ['POST'])]
    public function createEtape(Request $request): JsonResponse
    {
        /** @var CreateOrUpdateEtapeInput $input */
        $input = $this->serializer->deserialize(
            $request->getContent(),
            CreateOrUpdateEtapeInput::class,
            'json'
        );

        $violations = $this->validator->validate($input);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors(
                $this->errorMapper->map($violations)
            );
        }

        $id = $this->writeService->createEtape($input);

        return $this->json(['id' => $id], 201);
    }

    #[Route('/etapes/{id<\d+>}', name: 'etape_update', methods: ['PUT'])]
    public function updateEtape(Etape $etape, Request $request): JsonResponse
    {
        /** @var CreateOrUpdateEtapeInput $input */
        $input = $this->serializer->deserialize(
            $request->getContent(),
            CreateOrUpdateEtapeInput::class,
            'json'
        );

        $violations = $this->validator->validate($input);
        if (count($violations) > 0) {
            throw ApiValidationException::fromErrors(
                $this->errorMapper->map($violations)
            );
        }

        $this->writeService->updateEtape($etape, $input);

        return $this->json(null, 204);
    }
}