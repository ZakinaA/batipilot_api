<?php

namespace App\Dto\Chantier\Input;

use Symfony\Component\Validator\Constraints as Assert;

class ChantierPosteItemInput
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $posteId;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(0)]
    public float $montantHT;

    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual(0)]
    public float $montantTTC;

    #[Assert\GreaterThanOrEqual(0)]
    public ?float $montantFournitures = 0;

    #[Assert\GreaterThanOrEqual(0)]
    public ?float $nbJoursTravailles = 0;

    #[Assert\GreaterThanOrEqual(0)]
    public ?float $montantPrestataire = 0;

    #[Assert\Length(max: 120)]
    public ?string $nomPrestataire = null;
}