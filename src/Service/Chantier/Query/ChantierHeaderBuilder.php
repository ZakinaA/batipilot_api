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
        $dto->nomClient = $chantier->getClient()?->getNom() ?? $chantier->getClient()?->getRaisonSociale();
        $dto->ville = $chantier->getVille();
        $dto->totalHT = $this->calculator->getTotalHT($chantier);
        $dto->totalTTC = $this->calculator->getTotalTTC($chantier);
        $dto->equipe = $chantier->getEquipe()?->getNom();

        return $dto;
    }
}