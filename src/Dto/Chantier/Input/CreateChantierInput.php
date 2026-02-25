<?php

namespace App\Dto\Chantier\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateChantierInput
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $clientId;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $equipeId;

    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    public string $adresse;

    #[Assert\Length(max: 5)]
    public ?string $copos = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    public string $ville;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $dateDebutPrevue;

    #[Assert\Date]
    public ?string $dateFin = null;

    public ?float $surfacePlancher = null;
    public ?float $surfaceHabitable = null;
    public ?int $distanceDepot = null;
    public ?int $tempsTrajet = null;
    public ?float $coefficient = null;

    #[Assert\Length(max: 255)]
    public ?string $alerte = null;
}