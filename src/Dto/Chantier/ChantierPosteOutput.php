<?php
namespace App\Dto\Chantier;

class ChantierPosteOutput
{
    public int $id;
    public string $libelle;
    public ?float $montantHT = null;
    public ?float $montantTTC = null;
    public ?float $montantFournitures = null;
    public ?float $nbJoursTravailles = null;
    public ?float $montantPrestataire = null;

    /** @var EtapeOutput[] */
    public array $etapes = [];
}