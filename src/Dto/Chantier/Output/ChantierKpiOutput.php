<?php
namespace App\Dto\Chantier\Output;

use App\Dto\Chantier\ChantierPosteKpiOutput;
use App\Dto\Chantier\Commun\ChantierHeaderOutput;
use App\Dto\Chantier\Commun;
use App\Dto\Chantier\Commun\ChantierTotauxOutput; 

class ChantierKpiOutput
{
    public ChantierHeaderOutput $header;
    public ChantierTotauxOutput $totaux;

    /** @var ChantierPosteKpiOutput[] */
    public array $postes = [];
}