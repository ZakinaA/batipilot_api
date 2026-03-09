<?php

namespace App\Dto\Referentiels\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrUpdateEtapeInput
{
    #[Assert\NotBlank(message: 'Le libellé est requis.')]
    #[Assert\Length(max: 120)]
    public ?string $libelle = null;

    #[Assert\NotNull(message: 'Le poste est requis.')]
    #[Assert\Positive(message: 'Le poste est invalide.')]
    public ?int $posteId = null;

    #[Assert\NotNull(message: 'Le format est requis.')]
    #[Assert\Positive(message: 'Le format est invalide.')]
    public ?int $etapeFormatId = null;

    #[Assert\Type(type: 'bool')]
    public ?bool $archive = false;
}