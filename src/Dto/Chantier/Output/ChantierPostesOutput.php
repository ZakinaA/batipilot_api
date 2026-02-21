<?php

namespace App\Dto\Chantier\Output;

class ChantierPostesOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;

    /** @var ChantierPosteEtapesOutput[] */
    public array $postes = [];
}