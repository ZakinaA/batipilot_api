<?php

namespace App\Dto\Chantier\Common;

class ChantierHeaderOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;
    public float $totalHT = 0.0;
    public float $totalTTC = 0.0;
    public ?string $nomEquipe = null;
}