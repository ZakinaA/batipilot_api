<?php
namespace App\Dto\Chantier;

class ChantierMiniOutput
{
    public int $id;
    public ?string $adresse = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateFin = null;
}