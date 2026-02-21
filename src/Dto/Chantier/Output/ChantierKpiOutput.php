<?php
namespace App\Dto\Chantier\Output;

use App\Dto\Chantier\ChantierPosteKpiOutput;
use App\Dto\Chantier\Commun\ChantierHeaderOutput;

class ChantierKpiOutput
{
    public ChantierHeaderOutput $header;
    //public int $id;
    //public ?string $nomClient = null;
    //public ?string $ville = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateReception = null;
    public ?float $coefficient = null;
    //public ?string $equipe = null;

    // Totaux chantier (par défaut 0)
    public float $totalHT = 0.0;
    public float $totalTTC = 0.0;
    public float $totalFournitures = 0.0;
    public float $totalNbJoursTravailles = 0.0; // ou int si c’est un entier
    public int $totalNbTrajets = 0;
    public float $totalPrestataire = 0.0;
    public float $totalMainOeuvre = 0.0;
    public float $totalMainOeuvreSansTransport = 0.0;
    public float $totalTransport = 0.0;
    public float $totalCout = 0.0;
    public float $marge = 0.0;
    public float $tauxMarge = 0.0;

    /** @var ChantierPosteKpiOutput[] */
    public array $postes = [];
}