<?php

namespace App\Dto\Etape;

class EtapeValueOutput
{
    public int $id;
    public ?string $libelle = null;

    // ex: "oui ou non", "date", etc (utile même si le front affiche juste displayValue)
    public ?string $format = null;

    // prêt à afficher : "Oui/Non", "10/02/2026", "12,5", etc.
    public ?string $displayValue = null;
}