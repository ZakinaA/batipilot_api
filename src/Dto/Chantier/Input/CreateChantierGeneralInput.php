<?php

namespace App\Dto\Chantier\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateChantierGeneralInput
{
    #[Assert\NotNull(message: "L'équipe est requise.")]
    #[Assert\Positive(message: "L'équipe est invalide.")]
    public ?int $equipeId = null;

    #[Assert\NotBlank(message: "L'adresse est requise.")]
    #[Assert\Length(max: 120)]
    public ?string $adresse = null;

    #[Assert\Length(max: 5)]
    public ?string $copos = null;

    #[Assert\NotBlank(message: 'La ville est requise.')]
    #[Assert\Length(max: 120)]
    public ?string $ville = null;

    /*#[Assert\NotBlank(message: 'La date de début prévue est requise.')]*/
    #[Assert\Date(message: 'La date de démarrage est invalide.')]
    public ?string $dateDemarrage= null;

    #[Assert\Date(message: 'La date de réception est invalide.')]
    public ?string $dateReception = null;

    #[Assert\Type(type: 'numeric')]
    public ?float $surfacePlancher = null;

    #[Assert\Type(type: 'numeric')]
    public ?float $surfaceHabitable = null;

    #[Assert\Type(type: 'integer')]
    public ?int $distanceDepot = null;

    #[Assert\Type(type: 'integer')]
    public ?int $tempsTrajet = null;

    #[Assert\Type(type: 'numeric')]
    public ?float $coefficient = null;

    #[Assert\Length(max: 255)]
    public ?string $alerte = null;
}