<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\Output\List\ChantierListParEtatOutput;
use App\Dto\Chantier\Output\List\ChantierListItemOutput;
use App\Entity\Chantier;
use App\Repository\ChantierRepository;
use App\Service\Chantier\Domain\ChantierEtatManager;
use App\Service\Chantier\Domain\ChantierFinanceCalculator;
use App\Service\Chantier\Query\ChantierHeaderBuilder;

class ChantierListQuery
{
    public function __construct(
        private ChantierRepository $chantierRepository,
        private ChantierEtatManager $etatManager,
        private ChantierFinanceCalculator $calculator,
        private ChantierHeaderBuilder $headerBuilder
    ) {}

    public function listParEtat(): ChantierListParEtatOutput
    {
        $chantiers = $this->chantierRepository->findAvecClientEtPostes();

        $out = new ChantierListParEtatOutput();

        foreach ($chantiers as $chantier) {
            $item = $this->mapToListItem($chantier);
            $etat = $this->etatManager->getEtat($chantier);
            

            match ($etat) {
                'demarre' => $out->demarres[] = $item,
                'a_venir' => $out->aVenir[] = $item,
                'termine' => $out->termines[] = $item,
                default => $out->aVenir[] = $item,
            };
        }

        return $out;
    }

    private function mapToListItem(Chantier $chantier): ChantierListItemOutput
    {
        $dto = new ChantierListItemOutput();
        $dto->header = $this->headerBuilder->build($chantier);
        $dto->totaux = $this->calculator->calculate($chantier);
        return $dto;
    }
}