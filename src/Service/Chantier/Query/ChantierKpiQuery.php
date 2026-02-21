<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Output\ChantierKpiOutput;
use App\Dto\Chantier\Output\ChantierPosteKpiOutput;
use App\Entity\Chantier;

class ChantierKpiQuery
{
    public function __construct(
        private ChantierHeaderBuilder $headerBuilder
    ) {}

    public function kpi(Chantier $chantier): ChantierKpiOutput
    {
        $dto = new ChantierKpiOutput();
        $dto->header = $this->headerBuilder->build($chantier);

        foreach ($chantier->getChantierPostes() as $cp) {
            $posteDto = new ChantierPosteKpiOutput();
            $posteDto->id = $cp->getId();
            $posteDto->libelle = $cp->getPoste()->getLibelle();

            $posteDto->montantHT = (float) ($cp->getMontantHT() ?? 0.0);
            $posteDto->montantTTC = (float) ($cp->getMontantTTC() ?? 0.0);
            $posteDto->montantFournitures = (float) ($cp->getMontantFournitures() ?? 0.0);
            $posteDto->nbJoursTravailles = (float) ($cp->getNbJoursTravailles() ?? 0.0);
            $posteDto->montantPrestataire = (float) ($cp->getMontantPrestataire() ?? 0.0);

            $posteDto->nbTrajets = (int) (ceil($posteDto->nbJoursTravailles) * 2);
            $posteDto->montantMainOeuvre = $posteDto->nbJoursTravailles * (float) ($chantier->getCoefficient() ?? 0.0);
            $posteDto->montantCoutPoste = round($posteDto->montantFournitures + $posteDto->montantPrestataire + $posteDto->montantMainOeuvre, 2);
            $posteDto->margePoste = round($posteDto->montantHT - $posteDto->montantCoutPoste, 2);
            $posteDto->tauxMargePoste = $this->safePercent($posteDto->margePoste, $posteDto->montantCoutPoste);

            // cumuls
            $dto->totalHT = round($dto->totalHT + $posteDto->montantHT, 2);
            $dto->totalTTC = round($dto->totalTTC + $posteDto->montantTTC, 2);
            $dto->totalFournitures = round($dto->totalFournitures + $posteDto->montantFournitures, 2);
            $dto->totalPrestataire = round($dto->totalPrestataire + $posteDto->montantPrestataire, 2);
            $dto->totalMainOeuvre = round($dto->totalMainOeuvre + $posteDto->montantMainOeuvre, 2);
            $dto->totalNbTrajets += $posteDto->nbTrajets;

            $dto->postes[] = $posteDto;
        }

        $tempsTrajet = (float) ($chantier->getTempsTrajet() ?? 0.0);
        $coefficient = (float) ($chantier->getCoefficient() ?? 0.0);

        $dto->totalTransport = $this->calculCoutTrajet($dto->totalNbTrajets, $tempsTrajet, $coefficient);
        $dto->totalMainOeuvreSansTransport = round($dto->totalMainOeuvre - $dto->totalTransport, 2);

        $dto->totalCout = round($dto->totalFournitures + $dto->totalMainOeuvre + $dto->totalPrestataire, 2);
        $dto->marge = round($dto->totalHT - $dto->totalCout, 2);
        $dto->tauxMarge = $this->safePercent($dto->marge, $dto->totalCout);

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

    private function safePercent(float $numerator, float $denominator): float
    {
        if ($denominator <= 0.0) {
            return 0.0;
        }
        return round(($numerator / $denominator) * 100, 2);
    }
}