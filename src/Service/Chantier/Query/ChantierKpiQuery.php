<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Output\Kpi\ChantierKpiOutput;
use App\Dto\Chantier\Output\Kpi\ChantierKpiPosteOutput;
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
        
        $coef = (float) ($chantier->getCoefficient() ?? 0.0);
        $tempsTrajet = (float) ($chantier->getTempsTrajet() ?? 0.0);

        foreach ($chantier->getChantierPostes() as $cp) {
            $p = $cp->getPoste();

            $posteDto = new ChantierKpiPosteOutput();
            $posteDto->id = (int) ($p?->getId() ?? 0);
            $posteDto->libelle = (string) ($p?->getLibelle() ?? '');

            $posteDto->montantHT = (float) ($cp->getMontantHT() ?? 0.0);
            $posteDto->montantTTC = (float) ($cp->getMontantTTC() ?? 0.0);
            $posteDto->montantFournitures = (float) ($cp->getMontantFournitures() ?? 0.0);
            $posteDto->nbJoursTravailles = (float) ($cp->getNbJoursTravailles() ?? 0.0);
            $posteDto->montantPrestataire = (float) ($cp->getMontantPrestataire() ?? 0.0);

            $nbTrajets = (int) (ceil((float) ($cp->getNbJoursTravailles() ?? 0.0)) * 2);
            $posteDto->nbTrajets = $nbTrajets;

            $mainOeuvre = (float) ($cp->getNbJoursTravailles() ?? 0.0) * $coef;
            $posteDto->montantMainOeuvre = round($mainOeuvre, 2);

            $transport = $this->calculCoutTrajet($nbTrajets, $tempsTrajet, $coef);

            $coutPoste = (float) $posteDto->montantFournitures
                + (float) $posteDto->montantPrestataire
                + (float) $posteDto->montantMainOeuvre;

            $posteDto->montantCoutPoste = round($coutPoste, 2);
            $posteDto->margePoste = round(((float) $posteDto->montantHT) - $posteDto->montantCoutPoste, 2);
            $posteDto->tauxMargePoste = $posteDto->montantCoutPoste > 0
                ? round(($posteDto->margePoste / $posteDto->montantCoutPoste) * 100, 2)
                : 0.0;

            $dto->postes[] = $posteDto;
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

}