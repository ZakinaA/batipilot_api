<?php

namespace App\Service;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierMiniOutput;
use App\Dto\Client\ChantierClientOutput;
use App\Dto\Chantier\ChantierPosteOutput;
use App\Dto\Chantier\ChantierKpiOutput;
use App\Dto\Chantier\ChantierPosteKpiOutput;
use App\Dto\Chantier\ChantierListParEtatOutput;
use App\Dto\Chantier\ChantierOverviewOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Dto\Chantier\ChantierListItemOutput;
use App\Entity\Chantier;
use App\Dto\Chantier\ChantierPostesEtapesOutput;
use App\Dto\Chantier\ChantierPosteEtapesOutput;
use App\Dto\Etape\EtapeValueOutput;
use App\Entity\ChantierEtape;
use App\Entity\Etape;

class ChantierService
{
    public function __construct(
        private ChantierRepository $chantierRepository
    ) {}

    /* Liste les chantiers démarrés, à venir et terminés
     * Renvoie ChantierListParEtatOutput qui est un array des 3 listes
     */
    public function list(): ChantierListParEtatOutput
    {
        //$chantiers = $this->chantierRepository->findAll();
        $chantiers = $this->chantierRepository->findAvecClientEtPostes();

        $list_out = new ChantierListParEtatOutput();

        foreach ($chantiers as $chantier) {
            $chantierOut = $this->mapToListItem($chantier);
            $etat = $this->getEtatChantier($chantier);

            match ($etat) {
                'demarre' => $list_out->demarres[] = $chantierOut,
                'a_venir' => $list_out->aVenir[] = $chantierOut,
                'termine' => $list_out->termines[] = $chantierOut,
                default => $list_out->aVenir[] = $chantierOut,
            };
        }
        return $list_out;
    }
     /**
     * Retourne les informations générales du chantier + totalHT
     */
    public function showOverview(Chantier $chantier): ChantierOverviewOutput
    {

        $dto = new ChantierOverviewOutput();
        $dto->id = $chantier->getId();
        $dto->adresse = $chantier->getAdresse();
        $dto->copos = $chantier->getCopos();
        $dto->ville = $chantier->getVille();
        $dto->dateDebutPrevue = $chantier->getDateDebutPrevue();
        $dto->dateDemarrage = $chantier->getDateDemarrage();
        $dto->dateReception = $chantier->getDateReception();
        $dto->dateFin = $chantier->getDateFin();
        $dto->surfacePlancher = $chantier->getSurfacePlancher();
        $dto->surfaceHabitable = $chantier->getSurfaceHabitable();
        $dto->distanceDepot = $chantier->getDistanceDepot();
        $dto->tempsTrajet = $chantier->getTempsTrajet();
        $dto->coefficient = $chantier->getCoefficient();
        $dto->alerte = $chantier->getAlerte();

        // variables de calcul
        $dto->totalHT = $this->calculTotalHt($chantier);

        // Équipe : juste le nom
        if ($chantier->getEquipe()) {
            $dto->equipe = $chantier->getEquipe()->getNom();
        }

        // Infos Client
        if ($chantier->getClient()) {
            $clientDto = new ClientDetailOutput();
            //$clientDto->id = $chantier->getClient()->getId();
            $clientDto->nom = $chantier->getClient()->getNom();
            $clientDto->prenom = $chantier->getClient()->getPrenom();
            $clientDto->telephone = $chantier->getClient()->getTelephone();
            $clientDto->mail = $chantier->getClient()->getMail();
            $dto->client = $clientDto;
        }
        return $dto ;
    }


    /**
     * Retourne les informations financières du chantier
     */
    public function showKpi(Chantier $chantier): ChantierKpiOutput
    {
 
        $dto = new ChantierKpiOutput();
        $dto->id = $chantier->getId();
        $dto->nomClient = $chantier->getClient()->getNom();
        $dto->ville = $chantier->getVille();
        // Équipe : juste le nom
        if ($chantier->getEquipe()) {
            $dto->equipe = $chantier->getEquipe()->getNom();
        }   

        foreach ($chantier->getChantierPostes() as $cp) {
            $posteDto = new ChantierPosteKpiOutput();
            $posteDto->id = $cp->getId();
            $posteDto->libelle = $cp->getPoste()->getLibelle();
            
            $posteDto->montantHT = (float) ($cp->getMontantHT() ?? 0.0);
            $posteDto->montantTTC = (float) ($cp->getMontantTTC() ?? 0.0);
            $posteDto->montantFournitures = (float) ($cp->getMontantFournitures() ?? 0.0);
            $posteDto->nbJoursTravailles = (float) ($cp->getNbJoursTravailles() ?? 0.0);
            $posteDto->montantPrestataire = (float) ($cp->getMontantPrestataire() ?? 0.0);
            $posteDto->nbTrajets = ceil($posteDto->nbJoursTravailles)*2;
            $posteDto->montantMainOeuvre =  $posteDto->nbJoursTravailles * $chantier->getCoefficient() ;
            $posteDto->montantCoutPoste = round($posteDto->montantFournitures + $posteDto->montantPrestataire + $posteDto->montantMainOeuvre,2);
            $posteDto->margePoste = round($posteDto->montantHT -$posteDto->montantCoutPoste ,2) ;
            //$posteDto->tauxMargePoste = round( (($posteDto->montantHT - $posteDto->montantCoutPoste) / $posteDto->montantCoutPoste *100),2);
            $posteDto->tauxMargePoste = $this->safePercent($posteDto->margePoste,$posteDto->montantCoutPoste);
             
            // calculs : cumuls pour chaque poste
            $dto->totalHT = round($dto->totalHT + (float) $posteDto->montantHT, 2);
            $dto->totalTTC = round($dto->totalTTC + (float) $posteDto->montantTTC, 2);
            $dto->totalFournitures = round($dto->totalFournitures + (float) $posteDto->montantFournitures, 2);
            $dto->totalPrestataire = round($dto->totalPrestataire + (float) $posteDto->montantPrestataire, 2);
            $dto->totalMainOeuvre = round($dto->totalMainOeuvre + (float) $posteDto->montantMainOeuvre, 2);
            $dto->totalNbTrajets += (int) $posteDto->nbTrajets;
            $dto->postes[] = $posteDto;
        }
        // fin de la boucle sur les postes

        // calcul du cout total chantier
        $tempsTrajet = (float) ($chantier->getTempsTrajet() ?? 0.0);
        $coefficient = (float) ($chantier->getCoefficient() ?? 0.0);

        $dto->totalTransport = $this->calculCoutTrajet($dto->totalNbTrajets,$tempsTrajet,$coefficient);
        $dto->totalMainOeuvreSansTransport = round($dto->totalMainOeuvre - $dto->totalTransport,2) ;

        $dto->totalCout = round($dto->totalFournitures + $dto->totalMainOeuvre+ $dto->totalPrestataire, 2);
        $dto->marge = round($dto->totalHT - $dto->totalCout,2);
        //calcul de la marge
        $dto->tauxMarge = $this->safePercent($dto->totalHT - $dto->totalCout,$dto->totalCout);
    
        return $dto ;
    }

   

    // Mappe un chantier en Dto Chantier plus simple utilisé dans la liste des chantiers
    private function mapToListItem(Chantier $chantier): ChantierListItemOutput
    {
        $dto = new ChantierListItemOutput();
        $dto->id = (int) $chantier->getId();
        $dto->ville = $chantier->getVille();
        $dto->dateDemarrage = $chantier->getDateDemarrage();
        $dto->dateReception = $chantier->getDateReception();

        $client = $chantier->getClient();
        $dto->nomClient = $client ? ($client->getNom() ?? $client->getRaisonSociale() ?? null) : null; 
        $dto->totalHT = $this->calculTotalHt($chantier);

        return $dto;
    }

    private function calculTotalHt(Chantier $chantier): float
    {
        $total = 0.0;
        foreach ($chantier->getChantierPostes() as $chantierPoste) {
            $total += (float) ($chantierPoste->getMontantHT() ?? 0.0);
        }
        // arrondi 
        return round($total, 2);
    }

    /**
     * Retourne l'état en string selon les dates
     * Démarré : date de démarrage <= date du jour
     * A venir : date de démarrage > date du jour
     * Terminé : date de réception 
     */
    private function getEtatChantier(Chantier $chantier): string
    {
        $today = new \DateTimeImmutable('today');

        if ($chantier->getDateReception() !== null && $chantier->getDateReception() < $today ) {
            return 'termine';
        }

        if ($chantier->getDateDemarrage() !== null && $chantier->getDateDemarrage() <= $today ) {
            return 'demarre';
        }
        if ($chantier->getDateDemarrage() !== null && $chantier->getDateDemarrage() > $today ) {
            return 'a_venir';
        }
        return 'a_venir';
    }

   

    // 1 journée = 7h = 420 min
    // coutTrajetChantier = nbTrajets * 420/ coefficient
    private function calculCoutTrajet(int $nbTrajets, float $tempsTrajet, float $coefficient): float
    {
        if ($nbTrajets <= 0 || $tempsTrajet <= 0.0 || $coefficient <= 0.0) {
            return 0.0;
        }

        // coût/minute = coefficient / 420 (7h)
        $coutParMinute = $coefficient / 420;

        return round($nbTrajets * $tempsTrajet * $coutParMinute,2);
    }

    private function safePercent(float $numerator, float $denominator): float
    {
        if ($denominator <= 0.0) {
            return 0.0;
        }
        return round(($numerator / $denominator) * 100, 2);
    }
   


public function showEtapes(Chantier $chantier): ChantierPostesEtapesOutput
{
    $dto = new ChantierPostesEtapesOutput();
    $dto->id = (int) $chantier->getId();
    $dto->nomClient = $chantier->getClient()?->getNom();
    $dto->ville = $chantier->getVille();

    $index = $this->indexChantierEtapesByEtapeId($chantier);

    foreach ($chantier->getChantierPostes() as $cp) {
        $posteDto = new ChantierPosteEtapesOutput();
        $posteDto->id = (int) $cp->getId();
        $posteDto->libelle = $cp->getPoste()?->getLibelle();
        $posteDto->montantHT = (float) ($cp->getMontantHT() ?? 0.0);

        foreach ($cp->getPoste()->getEtapes() as $etape) {
            $chantierEtape = $index[$etape->getId()] ?? null;
            if (!$chantierEtape) {
                continue;
            }
            $posteDto->etapes[] = $this->mapEtapeDisplayValue($etape, $chantierEtape);
        }

        $dto->postes[] = $posteDto;
    }

    return $dto;
}

/** @return array<int, ChantierEtape> */
private function indexChantierEtapesByEtapeId(Chantier $chantier): array
{
    $index = [];
    foreach ($chantier->getChantierEtapes() as $ce) {
        $etapeId = $ce->getEtape()?->getId();
        if ($etapeId !== null) {
            $index[(int) $etapeId] = $ce;
        }
    }
    return $index;
}

private function mapEtapeDisplayValue(Etape $etape, ChantierEtape $chantierEtape): EtapeValueOutput
{
    $dto = new EtapeValueOutput();
    $dto->id = (int) $etape->getId();
    $dto->libelle = $etape->getLibelle();

    $formatOriginal = $etape->getEtapeFormat()?->getLibelle();
    $dto->format = $formatOriginal;

    $formatLabel = strtolower(trim($formatOriginal ?? ''));

    switch ($formatLabel) {

        case 'oui ou non':
            $b = $chantierEtape->isValBoolean();
            $dto->rawValue = $b; // bool|null
            $dto->displayValue = $b === null ? null : ($b ? 'Oui' : 'Non');
            break;

        case 'nombre entier':
            $i = $chantierEtape->getValInteger();
            $dto->rawValue = $i; // int|null
            $dto->displayValue = $i === null ? null : (string) $i;
            break;

        case 'nombre décimal':
        case 'nombre decimal':
            $f = $chantierEtape->getValFloat();
            $dto->rawValue = $f; // float|null

            if ($f === null) {
                $dto->displayValue = null;
            } else {
                // format FR : espace milliers + virgule décimale
                $dto->displayValue = rtrim(
                    rtrim(number_format($f, 2, ',', ' '), '0'),
                    ','
                );
            }
            break;

        case 'texte':
            $t = $chantierEtape->getValText();
            $dto->rawValue = $t; // string|null
            $dto->displayValue = ($t === null || trim($t) === '') ? null : $t;
            break;

        case 'date':
            $d = $chantierEtape->getValDate();
            $dto->rawValue = $d?->format('Y-m-d');      // format HTML input[type=date]
            $dto->displayValue = $d?->format('d/m/Y');  // affichage FR
            break;

        case 'date et heure':
            $dt = $chantierEtape->getValDateHeure();
            $dto->rawValue = $dt?->format(\DateTimeInterface::ATOM); // ISO 8601
            $dto->displayValue = $dt?->format('d/m/Y H:i');
            break;

        default:
            // Sécurité si jamais un nouveau format apparaît
            $dto->rawValue = null;
            $dto->displayValue = null;
            break;
    }

    return $dto;
}



    

}