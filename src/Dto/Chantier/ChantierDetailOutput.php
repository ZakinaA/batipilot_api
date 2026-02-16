<?php
namespace App\Dto\Chantier;

use App\Dto\Client\ClientDetailOutput;

class ChantierDetailOutput
{
    public int $id;
    public ?string $adresse = null;
    public ?string $copos = null;
    public ?string $ville = null;
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
    public ?string $equipe = null;
    public ?ClientDetailOutput $client = null;
    
    /** @var ChantierPosteOutput[] */
    public array $postes = [];
}