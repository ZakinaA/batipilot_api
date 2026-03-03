<?php
namespace App\Dto\Chantier\Output\List;

use App\Dto\Chantier\Commun\ChantierHeaderOutput;

class ChantierListItemOutput
{
    public ChantierHeaderOutput $header;
    public ChantierTotauxOutput $totaux;
    
}