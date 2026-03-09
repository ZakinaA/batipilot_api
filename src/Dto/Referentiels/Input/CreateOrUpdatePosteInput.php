<?php

namespace App\Dto\Referentiels\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrUpdatePosteInput
{
    #[Assert\NotBlank(message: 'Le libellé est requis.')]
    #[Assert\Length(max: 120)]
    public ?string $libelle = null;

    #[Assert\NotNull(message: 'La TVA est requise.')]
    #[Assert\Type(type: 'numeric', message: 'La TVA doit être numérique.')]
    public ?float $tva = null;

    #[Assert\NotNull(message: "L'ordre est requis.")]
    #[Assert\Type(type: 'integer', message: "L'ordre doit être un entier.")]
    public ?int $ordre = null;

    #[Assert\Type(type: 'bool')]
    public ?bool $archive = false;
}