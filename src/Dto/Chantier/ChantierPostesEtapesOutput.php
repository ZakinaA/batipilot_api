<?php

namespace App\Dto\Chantier;

class ChantierPostesEtapesOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;

    /** @var ChantierPosteEtapesOutput[] */
    public array $postes = [];
}