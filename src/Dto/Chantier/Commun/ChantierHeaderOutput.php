<?php

namespace App\Dto\Chantier\Commun;

class ChantierHeaderOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateReception = null;
    public ?float $coefficient = null;
    public ?string $nomEquipe = null;
}