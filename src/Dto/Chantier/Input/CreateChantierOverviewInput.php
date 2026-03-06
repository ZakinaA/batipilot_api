<?php

namespace App\Dto\Chantier\Input;

use Symfony\Component\Validator\Constraints as Assert;
use App\Dto\Client\Input\CreateClientInput;

class CreateChantierOverviewInput
{
     #[Assert\NotNull(message: 'Le bloc client est requis.')]
    #[Assert\Valid]
    public ?CreateClientInput $client = null;

    #[Assert\NotNull(message: 'Le bloc chantier est requis.')]
    #[Assert\Valid]
    public ?CreateChantierGeneralInput $chantier = null;
}