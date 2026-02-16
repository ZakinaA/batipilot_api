<?php
namespace App\Dto\Chantier;

use App\Dto\Client\ClientDetailOutput;

class ChantierDetailOutput
{
    public int $id;
    public ?string $adresse = null;
    public ?\DateTime $dateDebutPrevue = null;
    public ?\DateTime $dateDemarrage = null;
    public ?\DateTime $dateFin = null;
    public ?ClientDetailOutput $client = null;
    /** @var ChantierPosteOutput[] */
    public array $postes = [];
}