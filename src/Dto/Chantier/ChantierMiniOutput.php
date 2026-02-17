<?php
namespace App\Dto\Chantier;

class ChantierMiniOutput
{
    public int $id;
    public ?string $nomClient = null;
    public ?string $ville = null;
    public ?\DateTimeInterface $dateDebutPrevue = null;
    public ?\DateTimeInterface $dateDemarrage = null;
    public ?\DateTimeInterface $dateReception = null;
    public ?\DateTimeInterface $dateFin = null;
    
}