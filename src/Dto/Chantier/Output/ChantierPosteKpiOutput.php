<?php
namespace App\Dto\Chantier\Output;

class ChantierPosteKpiOutput
{
    public int $id;
    public string $libelle;
    public ?float $montantHT = null;
    public ?float $montantTTC = null;
    public ?float $montantFournitures = null;
    public ?float $nbJoursTravailles = null;
    public ?int $nbTrajets = null;
    public ?float $montantPrestataire = null;
    public ?float $montantMainOeuvre = null;
    public ?float $montantCoutPoste= null;
    public ?float $margePoste = null;
    public ?float $tauxMargePoste = null;
    /* @var EtapeOutput[] */
   //public array $etapes = [];
}