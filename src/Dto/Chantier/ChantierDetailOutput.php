<?php
namespace App\Dto\Chantier;

class ChantierDetailOutput
{
    public int $id;
    public ?string $adresse = null;
    public ?\DateTime $dateDebutPrevue = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateFin = null;
    public ?ClientOutput $client = null;
    /** @var ChantierPosteOutput[] */
    public array $postes = [];
}