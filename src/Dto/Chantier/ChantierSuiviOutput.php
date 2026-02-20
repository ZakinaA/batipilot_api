<?php

namespace App\Dto\Chantier;

use App\Dto\Chantier\Commun\ChantierHeaderOutput;

class ChantierSuiviOutput
{
    public ChantierHeaderOutput $header;

    /** @var ChantierPosteEtapesOutput[] */
    public array $postes = [];
}