<?php

namespace App\Dto\Chantier\Input;

use Symfony\Component\Validator\Constraints as Assert;

class ChantierEtapeItemInput
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $etapeId;

    // On laisse optionnel, le service peut checker selon format
    #[Assert\Date]
    public ?string $valDate = null;

    // ISO 8601 recommand
    public ?string $valDateHeure = null;

    #[Assert\Length(max: 255)]
    public ?string $valText = null;

    public ?bool $valBoolean = null;
    public ?int $valInteger = null;
    public ?float $valFloat = null;
}