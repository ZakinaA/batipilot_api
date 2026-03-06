<?php

namespace App\Dto\Client\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateClientInput
{
    #[Assert\NotBlank(message: 'Le nom du client est requis.')]
    #[Assert\Length(max: 80)]
    public ?string $nom = null;

    #[Assert\Length(max: 80)]
    public ?string $prenom = null;

    #[Assert\Length(max: 14)]
    public ?string $telephone = null;

    #[Assert\Length(max: 120)]
    #[Assert\Email(message: 'Le mail client est invalide.')]
    public ?string $mail = null;
}