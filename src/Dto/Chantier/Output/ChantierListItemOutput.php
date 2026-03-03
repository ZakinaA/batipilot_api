<?php
namespace App\Dto\Chantier\Output;

use App\Dto\Chantier\Commun\ChantierHeaderOutput;

class ChantierListItemOutput
{
    public ChantierHeaderOutput $header;
    public ChantierTotauxOutput $totaux;
    
}