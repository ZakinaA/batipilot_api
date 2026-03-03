<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Output\Overview\ChantierOverviewOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Entity\Chantier;
use App\Service\Chantier\Domain\ChantierFinanceCalculator;

class ChantierOverviewQuery
{
    public function __construct(
        private ChantierHeaderBuilder $headerBuilder,
        private ChantierFinanceCalculator $calculator
    ) {}

    public function overview(Chantier $chantier): ChantierOverviewOutput
    {
        $dto = new ChantierOverviewOutput();
        $dto->header = $this->headerBuilder->build($chantier);
        $dto->totaux = $this->calculator->calculate($chantier);
        

        $dto->adresse = $chantier->getAdresse();
        $dto->copos = $chantier->getCopos();
        $dto->dateDebutPrevue = $chantier->getDateDebutPrevue();
        $dto->dateFin = $chantier->getDateFin();
        $dto->surfacePlancher = $chantier->getSurfacePlancher();
        $dto->surfaceHabitable = $chantier->getSurfaceHabitable();
        $dto->distanceDepot = $chantier->getDistanceDepot();
        $dto->tempsTrajet = $chantier->getTempsTrajet();
        $dto->coefficient = $chantier->getCoefficient();
        $dto->alerte = $chantier->getAlerte();

        if ($chantier->getClient()) {
            $clientDto = new ClientDetailOutput();
            $clientDto->nom = $chantier->getClient()->getNom();
            $clientDto->prenom = $chantier->getClient()->getPrenom();
            $clientDto->telephone = $chantier->getClient()->getTelephone();
            $clientDto->mail = $chantier->getClient()->getMail();
            $dto->client = $clientDto;
        }

        return $dto;
    }
}