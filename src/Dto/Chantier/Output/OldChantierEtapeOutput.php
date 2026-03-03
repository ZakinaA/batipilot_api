<?php
namespace App\Dto\Chantier\Output\Suivi;

class OldChantierEtapeOutput
{
    public int $id;
    public string $libelle;
    public ?bool $valBoolean = null;
    public ?int $valInteger = null;
    public ?float $valFloat = null;
    public ?string $valText = null;
    public ?\DateTime $valDate = null;
    public ?\DateTime $valDateHeure = null;
}