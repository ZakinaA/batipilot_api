<?php

namespace App\Service;

use App\Repository\ClientRepository;
use App\Repository\EquipeRepository;
use App\Repository\PosteRepository;
use App\Repository\EtapeRepository;

class ReferentielsService
{
    public function __construct(
        private ClientRepository $clientRepo,
        private EquipeRepository $equipeRepo,
        private PosteRepository $posteRepo,
        private EtapeRepository $etapeRepo,
    ) {}

    public function getAll(): array
    {
        $clients = array_map(fn($c) => [
            'id' => $c->getId(),
            'nom' => $c->getNom(),
            'prenom' => $c->getPrenom(),
        ], $this->clientRepo->findAll());

        $equipes = array_map(fn($e) => [
            'id' => $e->getId(),
            'nom' => $e->getNom(),
        ], $this->equipeRepo->findAll());

        $postes = array_map(fn($p) => [
            'id' => $p->getId(),
            'libelle' => $p->getLibelle(),
            'tva' => $p->getTva(),     // TVA par défaut (suggestion front)
            'ordre' => $p->getOrdre(),
        ], $this->posteRepo->findBy(['archive' => 0], ['ordre' => 'ASC']));

        $etapes = array_map(fn($e) => [
            'id' => $e->getId(),
            'libelle' => $e->getLibelle(),
            'posteId' => $e->getPoste()->getId(),
            'format' => $e->getEtapeFormat()?->getLibelle(), // "date", "datetime", "text"...
        ], $this->etapeRepo->findBy(['archive' => 0]));

        return [
            'clients' => $clients,
            'equipes' => $equipes,
            'postes' => $postes,
            'etapes' => $etapes,
        ];
    }

    public function getPostesAvecEtapes(): array
    {
        $postes = $this->posteRepo->findBy(['archive' => 0], ['ordre' => 'ASC']);

        return array_map(function ($poste) {
            $etapes = array_map(fn($etape) => [
                'id' => $etape->getId(),
                'libelle' => $etape->getLibelle(),
                'format' => $etape->getEtapeFormat()?->getLibelle(),
                'etapeFormatId' => $etape->getEtapeFormat()?->getId(),
                'archive' => $etape->getArchive(),
            ], array_filter(
                $poste->getEtapes()->toArray(),
                fn($etape) => $etape->getArchive() === 0
            ));

            return [
                'id' => $poste->getId(),
                'libelle' => $poste->getLibelle(),
                'tva' => $poste->getTva(),
                'ordre' => $poste->getOrdre(),
                'archive' => $poste->getArchive(),
                'etapes' => array_values($etapes),
            ];
        }, $postes);
    }
}