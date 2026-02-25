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
}