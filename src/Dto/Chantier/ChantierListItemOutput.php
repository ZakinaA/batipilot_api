<?php
namespace App\Dto\Chantier;

class ChantierListItemOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;
    public ?\DateTimeInterface $dateDemarrage = null;
    public ?\DateTimeInterface $dateReception = null;
    public float $totalHT = 0.0;
}