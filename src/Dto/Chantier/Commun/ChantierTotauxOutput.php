<?php
namespace App\Dto\Chantier\Commun;


class ChantierTotauxOutput
{
    public float $totalHT = 0.0;
    public float $totalTTC = 0.0;
    public float $totalFournitures = 0.0;
    public float $totalNbJoursTravailles = 0.0;
    public int $totalNbTrajets = 0;
    public float $totalPrestataire = 0.0;
    public float $totalMainOeuvre = 0.0;
    public float $totalMainOeuvreSansTransport = 0.0;
    public float $totalTransport = 0.0;
    public float $totalCout = 0.0;
    public float $marge = 0.0;
    public float $tauxMarge = 0.0;
}