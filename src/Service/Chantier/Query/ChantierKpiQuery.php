<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Output\ChantierKpiOutput;
use App\Dto\Chantier\Output\ChantierPosteKpiOutput;
use App\Entity\Chantier;
use App\Service\Chantier\Domain\ChantierFinanceCalculator;
use App\Service\Chantier\Query\ChantierHeaderBuilder;
use App\Dto\Chantier\Commun\ChantierTotauxOutput; 


class ChantierKpiQuery
{
    public function __construct(
        private ChantierHeaderBuilder $headerBuilder,
        private ChantierFinanceCalculator $calculator
    ) {}

    public function kpi(Chantier $chantier): ChantierKpiOutput
    {
         $dto = new ChantierKpiOutput();
    $dto->header = $this->headerBuilder->build($chantier);

    $dto->dateDemarrage = $chantier->getDateDemarrage();
    $dto->dateReception = $chantier->getDateReception();
    $dto->coefficient = $chantier->getCoefficient();

    $dto->totaux = $this->calculator->calculate($chantier);

    foreach ($chantier->getChantierPostes() as $cp) {
        // ton mapping posteDto (montantMainOeuvre, margePoste, etc.)
        // $dto->postes[] = $posteDto;
    }

    return $dto;
    }

    private function calculCoutTrajet(int $nbTrajets, float $tempsTrajet, float $coefficient): float
    {
        if ($nbTrajets <= 0 || $tempsTrajet <= 0.0 || $coefficient <= 0.0) {
            return 0.0;
        }
        $coutParMinute = $coefficient / 420;
        return round($nbTrajets * $tempsTrajet * $coutParMinute, 2);
    }

    /*private function safePercent(float $numerator, float $denominator): float
    {
        if ($denominator <= 0.0) {
            return 0.0;
        }
        return round(($numerator / $denominator) * 100, 2);
    }*/
}