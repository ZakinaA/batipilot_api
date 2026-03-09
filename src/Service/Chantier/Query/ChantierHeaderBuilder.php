<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Commun\ChantierHeaderOutput;
use App\Entity\Chantier;
use App\Service\Chantier\Domain\ChantierFinanceCalculator;


class ChantierHeaderBuilder
{
    public function __construct(private ChantierFinanceCalculator $calculator) {}

    public function build(Chantier $chantier): ChantierHeaderOutput
    {
        $dto = new ChantierHeaderOutput();
        $dto->id = (int) $chantier->getId();
        $dto->nomClient = $chantier->getClient()?->getNom() ?? "Client inconnu";
        $dto->ville = $chantier->getVille();
        $dto->dateDemarrage = $chantier->getDateDemarrage();
        $dto->dateReception = $chantier->getDateReception();
        $dto->coefficient = $chantier->getCoefficient();
        $dto->idEquipe = $chantier->getEquipe()?->getId();
        $dto->nomEquipe = $chantier->getEquipe()?->getNom();

        return $dto;
    }
}