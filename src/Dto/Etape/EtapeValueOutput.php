<?php

namespace App\Dto\Etape;

class EtapeValueOutput
{
    public int $id;
    public ?string $libelle = null;

    // ex: "oui ou non", "date", ...
    public ?string $format = null;

    // prêt à afficher
    public ?string $displayValue = null;

    // prêt à éditer (bool/int/float/string/date normalisée)
    public mixed $rawValue = null;
}