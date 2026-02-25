<?php
       
namespace App\Service\Chantier\Domain;

use App\Dto\Chantier\Commun;
use App\Entity\Chantier;
use App\Dto\Chantier\Commun\ChantierTotauxOutput;

final class ChantierFinanceCalculator
{
    public function calculate(Chantier $chantier): ChantierTotauxOutput
    {
        $s = new ChantierTotauxOutput();

        $coef = (float) ($chantier->getCoefficient() ?? 0.0);
        $tempsTrajet = (float) ($chantier->getTempsTrajet() ?? 0.0);

        foreach ($chantier->getChantierPostes() as $cp) {
            $ht = (float) ($cp->getMontantHT() ?? 0.0);
            $ttc = (float) ($cp->getMontantTTC() ?? 0.0);
            $fourn = (float) ($cp->getMontantFournitures() ?? 0.0);
            $prest = (float) ($cp->getMontantPrestataire() ?? 0.0);
            $jours = (float) ($cp->getNbJoursTravailles() ?? 0.0);

            $nbTrajets = (int) (ceil($jours) * 2);
            $mainOeuvre = $jours * $coef;

            $s->totalHT += $ht;
            $s->totalTTC += $ttc;
            $s->totalFournitures += $fourn;
            $s->totalPrestataire += $prest;
            $s->totalNbJoursTravailles += $jours;
            $s->totalNbTrajets += $nbTrajets;
            $s->totalMainOeuvre += $mainOeuvre;
        }

        $s->totalTransport = $this->calculCoutTrajet($s->totalNbTrajets, $tempsTrajet, $coef);
        $s->totalMainOeuvreSansTransport = round($s->totalMainOeuvre - $s->totalTransport, 2);

        $s->totalCout = round($s->totalFournitures + $s->totalMainOeuvre + $s->totalPrestataire, 2);
        $s->marge = round($s->totalHT - $s->totalCout, 2);
        $s->tauxMarge = $this->safePercent($s->marge, $s->totalCout);

        // arrondis finaux
        $s->totalHT = round($s->totalHT, 2);
        $s->totalTTC = round($s->totalTTC, 2);
        $s->totalFournitures = round($s->totalFournitures, 2);
        $s->totalPrestataire = round($s->totalPrestataire, 2);
        $s->totalMainOeuvre = round($s->totalMainOeuvre, 2);

        return $s;
    }

    private function calculCoutTrajet(int $nbTrajets, float $tempsTrajet, float $coefficient): float
    {
        if ($nbTrajets <= 0 || $tempsTrajet <= 0.0 || $coefficient <= 0.0) {
            return 0.0;
        }
        $coutParMinute = $coefficient / 420;
        return round($nbTrajets * $tempsTrajet * $coutParMinute, 2);
    }

    private function safePercent(float $numerator, float $denominator): float
    {
        return $denominator <= 0.0 ? 0.0 : round(($numerator / $denominator) * 100, 2);
    }
}
