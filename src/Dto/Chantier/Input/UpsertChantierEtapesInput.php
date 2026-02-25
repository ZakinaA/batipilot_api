<?php

namespace App\Dto\Chantier\Input;

use Symfony\Component\Validator\Constraints as Assert;

class UpsertChantierEtapesInput
{
    /** @var ChantierEtapeItemInput[] */
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    public array $items = [];
}