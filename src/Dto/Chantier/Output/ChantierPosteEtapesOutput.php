<?php

namespace App\Dto\Chantier\Output;

use App\Dto\Etape\EtapeValueOutput;
use App\Dto\Chantier\Commun\ChantierHeaderOutput;

class ChantierPosteEtapesOutput
{
    public int $id;
    public ?string $libelle = null;
    public float $montantHT = 0.0;
    public float $montantTTC = 0.0;

    /** @var EtapeValueOutput[] */
    public array $etapes = [];
}