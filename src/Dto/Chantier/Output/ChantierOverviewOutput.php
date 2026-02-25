<?php

namespace App\Dto\Chantier\Output;

use App\Dto\Client\ClientDetailOutput;
use App\Dto\Chantier\Commun\ChantierHeaderOutput;
use App\Dto\Chantier\Commun;

class ChantierOverviewOutput
{
    public ChantierHeaderOutput $header;
    //public int $id;
    public ?string $adresse = null;
    public ?string $copos = null;
    //public ?string $ville = null;
    public ?\DateTime $dateDebutPrevue = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateReception = null;
    public ?\DateTime $dateFin = null;
    public ?float $surfacePlancher = null;
    public ?float $surfaceHabitable = null;
    public ?int $distanceDepot = null;
    public ?int $tempsTrajet = null;
    public ?float $coefficient = null;
    public ?string $alerte = null;
    //public ?string $equipe = null;
    public ?ClientDetailOutput $client = null;

    public ChantierTotauxOutput $totaux;

    // variable de calcul
    public ?float $totalHT = null;


    // rajouter la pièce à demander : demande préalable ou permis de construire
    // mettre les conditions de la pièce demandée dans les paramètres de l'application
    
}