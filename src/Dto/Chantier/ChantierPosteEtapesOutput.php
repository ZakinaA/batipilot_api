<?php

namespace App\Dto\Chantier;

use App\Dto\Etape\EtapeValueOutput;

class ChantierPosteEtapeOutput
{
    public int $id;
    public ?string $libelle = null;
    public float $montantHT = 0.0;

    /** @var EtapeValueOutput[] */
    public array $etapes = [];
}