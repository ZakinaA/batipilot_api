<?php

namespace App\Service\Chantier\Domain;

use App\Entity\Chantier;

class ChantierFinanceCalculator
{
    public function getTotalHT(Chantier $chantier): float
    {
        $total = 0.0;
        foreach ($chantier->getChantierPostes() as $cp) {
            $total += (float) ($cp->getMontantHT() ?? 0.0);
        }
        return round($total, 2);
    }

    public function getTotalTTC(Chantier $chantier): float
    {
        $total = 0.0;
        foreach ($chantier->getChantierPostes() as $cp) {
            $total += (float) ($cp->getMontantTTC() ?? 0.0);
        }
        return round($total, 2);
    }
}