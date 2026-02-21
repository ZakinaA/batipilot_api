<?php
namespace App\Dto\Chantier\Output;

class ChantierListParEtatOutput
{
    /** @var ChantierListItemOutput[] */
    public array $demarres = [];

    /** @var ChantierListItemOutput[] */
    public array $aVenir = [];

    /** @var ChantierListItemOutput[] */
    public array $termines = [];
}