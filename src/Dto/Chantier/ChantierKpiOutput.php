<?php
namespace App\Dto\Chantier;

use App\Dto\Client\ChantierPosteKpiOutput;

class ChantierKpiOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateReception = null;
    public ?float $coefficient = null;
    public ?string $equipe = null;

    // variables totales chantier

    public ?float $totalHT = null;
    public ?float $totalTTC = null;
    public ?float $totalFournitures = null;
    public ?float $totalNbJoursTravailles = null;
    public ?int $totalNbTrajets = null;
    public ?float $totalPrestataire = null;
    public ?float $totalMainOeuvre = null;
    public ?float $totalMainOeuvreSansTransport = null;
    public ?float $totalTransport = null;
    public ?float $totalCout = null;
    public ?float $marge = null;
    public ?float $tauxMarge = null;

 

    /** @var ChantierPosteOutput[] */
    public array $postes = [];
}