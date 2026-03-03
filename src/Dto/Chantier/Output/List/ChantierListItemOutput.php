<?php
namespace App\Dto\Chantier\Output\List;

use App\Dto\Chantier\Commun\ChantierHeaderOutput;
use App\Dto\Chantier\Commun\ChantierTotauxOutput;

class ChantierListItemOutput
{
    public ChantierHeaderOutput $header;
    public ChantierTotauxOutput $totaux;
    
}