<?php
namespace App\Dto\Chantier;

class ChantierListParEtatOutput
{
    /** @var ChantierListItemOutput[] */
    public array $demarres = [];

    /** @var ChantierListItemOutput[] */
    public array $aVenir = [];

    /** @var ChantierListItemOutput[] */
    public array $termines = [];
}