<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Output\ChantierOverviewOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Entity\Chantier;

class ChantierOverviewQuery
{
    public function __construct(
        private ChantierHeaderBuilder $headerBuilder
    ) {}

    public function overview(Chantier $chantier): ChantierOverviewOutput
    {
        $dto = new ChantierOverviewOutput();
        $dto->header = $this->headerBuilder->build($chantier);

        $dto->adresse = $chantier->getAdresse();
        $dto->copos = $chantier->getCopos();
        $dto->dateDebutPrevue = $chantier->getDateDebutPrevue();
        $dto->dateDemarrage = $chantier->getDateDemarrage();
        $dto->dateReception = $chantier->getDateReception();
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