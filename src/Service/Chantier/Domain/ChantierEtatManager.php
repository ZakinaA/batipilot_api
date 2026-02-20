<?php

namespace App\Service\Chantier\Domain;

use App\Entity\Chantier;

class ChantierEtatManager
{
    
    /**
     * Retourne l'état en string selon les dates
     * Démarré : date de démarrage <= date du jour
     * A venir : date de démarrage > date du jour
     * Terminé : date de réception 
     */

    public function getEtat(Chantier $chantier): string
    {
        $today = new \DateTimeImmutable('today');

        if ($chantier->getDateReception() !== null && $chantier->getDateReception() < $today) {
            return 'termine';
        }

        if ($chantier->getDateDemarrage() !== null && $chantier->getDateDemarrage() <= $today) {
            return 'demarre';
        }

        return 'a_venir';
    }
}