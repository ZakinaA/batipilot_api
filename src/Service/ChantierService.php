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
            
            $posteDto->montantHT = $cp->getMontantHT();
            $posteDto->montantTTC = $cp->getMontantTTC();
            $posteDto->montantFournitures = $cp->getMontantFournitures();
            $posteDto->nbJoursTravailles = $cp->getNbJoursTravailles();
            $posteDto->montantPrestataire = $cp->getMontantPrestataire();
            $posteDto->nbTrajets = ceil($posteDto->nbJoursTravailles)*2;
            $posteDto->montantMainOeuvre =  $posteDto->nbJoursTravailles * $chantier->getCoefficient() ;
            $posteDto->montantCoutPoste = round($posteDto->montantFournitures + $posteDto->montantPrestataire + $posteDto->montantMainOeuvre,2);
            $posteDto->margePoste = round($posteDto->montantHT -$posteDto->montantCoutPoste ,2) ;
            $posteDto->tauxMargePoste = round( (($posteDto->montantHT - $posteDto->montantCoutPoste) / $posteDto->montantCoutPoste *100),2);
            
             
                // calculs : cumuls pour chaque poste
            $dto->totalHT = round($dto->totalHT + $cp->getMontantHT(), 2) ;
            $dto->totalTTC = round($dto->totalTTC + $cp->getMontantTTC(), 2) ;
            $dto->totalFournitures = round($dto->totalFournitures + $cp->getMontantFournitures(), 2); 
            $dto->totalMainOeuvre = round($dto->totalMainOeuvre + $posteDto->montantMainOeuvre, 2);
            $dto->totalPrestataire = round($dto->totalPrestataire + $cp->getMontantPrestataire(), 2);
            $dto->totalNbTrajets = $dto->totalNbTrajets + $posteDto->nbTrajets ;
                  
            /*foreach ($cp->getPoste()->getEtapes() as $etape) {
                $chantierEtape = $etape->getChantierEtapes()->filter(fn($ce) => $ce->getChantier()->getId() === $chantier->getId())->first();
                if ($chantierEtape) {
                    $etapeDto = new EtapeOutput();
                    $etapeDto->id = $etape->getId();
                    $etapeDto->libelle = $etape->getLibelle();
                    $etapeDto->valBoolean = $chantierEtape->isValBoolean();
                    $etapeDto->valInteger = $chantierEtape->getValInteger();
                    $etapeDto->valFloat = $chantierEtape->getValFloat();
                    $etapeDto->valText = $chantierEtape->getValText();
                    $etapeDto->valDate = $chantierEtape->getValDate();
                    $etapeDto->valDateHeure = $chantierEtape->getValDateHeure();

                    //$posteDto->etapes[] = $etapeDto;
                }
            }*/

            $dto->postes[] = $posteDto;
        }
        // fin de la boucle sur les postes

        // calcul du cout total chantier
        $dto->totalTransport = $this->calculCoutTrajet($dto->totalNbTrajets, $chantier->getTempsTrajet(), $chantier->getCoefficient());
        $dto->totalMainOeuvreSansTransport = round($dto->totalMainOeuvre - $dto->totalTransport,2) ;

        $dto->totalCout = round($dto->totalFournitures + $dto->totalMainOeuvre+ $dto->totalPrestataire, 2);
        $dto->marge = round($dto->totalHT - $dto->totalCout,2);
        //calcul de la marge
        $dto->tauxMarge = round((($dto->totalHT - $dto->totalCout) / $dto->totalCout)*100,2);
    
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

        if ($chantier->getDateReception() !== null && $chantier->getDateReception() > $today ) {
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
    private function calculCoutTrajet(int $nbTrajets, float $tempsTrajet, float $coeff): float
    {
        if ($nbTrajets <= 0 || $tempsTrajet <= 0 || $coeff <= 0) {
            return 0.0;
        }
        $coutParMinute = $coeff / 420;
        return round($nbTrajets * $tempsTrajet * $coutParMinute,2);
    }
   

}