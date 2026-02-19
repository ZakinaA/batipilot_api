<?php

namespace App\Service;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierMiniOutput;
use App\Dto\Client\ChantierClientOutput;
use App\Dto\Chantier\ChantierPosteOutput;
use App\Dto\Chantier\ChantierDetailOutput;
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
        if ($chantier->getDateReception() !== null || $chantier->getDateReception() > $today ) {
            return 'termine';
        }

        if ($chantier->getDateDemarrage() !== null && $chantier->getDateDemarrage() <= $today ) {
            return 'demarre';
        }
        if ($chantier->getDateDemarrage() !== null && $chantier->getDateDemarrage() > $today ) {
            return 'demarre';
        }
        return 'a_venir';
    }

    /**
     * Retourne le détail d’un chantier
     */
    public function showOverview($chantier): ChantierOverviewOutput
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
     * Retourne le détail d’un chantier
     */
    public function show($chantier): ChantierDetailOutput
    {

        $dto = new ChantierDetailOutput();
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
        $dto->totalVenduHT = 0 ;
        $dto->totalVenduTTC = 0;

        $dto->totalFournitures = 0;
        $dto->totalMainOeuvre = 0;
        $dto->totalPrestataire = 0;
        $dto->cout = 0;
        $dto->marge = 0;
       

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

       

        foreach ($chantier->getChantierPostes() as $cp) {
            $posteDto = new ChantierPosteOutput();
            $posteDto->id = $cp->getId();
            $posteDto->libelle = $cp->getPoste()->getLibelle();
            $posteDto->montantHT = $cp->getMontantHT();
            $posteDto->montantTTC = $cp->getMontantTTC();
            $posteDto->montantFournitures = $cp->getMontantFournitures();
            $posteDto->nbJoursTravailles = $cp->getNbJoursTravailles();
            $posteDto->montantPrestataire = $cp->getMontantPrestataire();
            $posteDto->coutMainOeuvre = round( ($cp->getNbJoursTravailles() * $chantier->getCoefficient()), 2);

            // calculs : cumuls pour chaque poste
            $dto->totalVenduHT = round($dto->totalVenduHT + $cp->getMontantHT(), 2) ;
            $dto->totalVenduTTC = round($dto->totalVenduTTC + $cp->getMontantTTC(), 2) ;

            $dto->totalFournitures = round($dto->totalFournitures + $cp->getMontantFournitures(), 2); 
            $dto->totalMainOeuvre = round($dto->totalMainOeuvre + $posteDto->coutMainOeuvre, 2);
            $dto->totalPrestataire = round($dto->totalPrestataire + $cp->getMontantPrestataire(), 2);
            
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

        // calcul du cout total chantier
        $dto->totalCout = round($dto->totalFournitures + $dto->totalMainOeuvre+ $dto->totalPrestataire, 2);

        //calcul de la marge
        $dto->marge = round((($dto->totalVenduHT - $dto->totalCout) / $dto->totalCout)*100,2);
    
        return $dto ;
    }

}