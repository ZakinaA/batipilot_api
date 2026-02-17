<?php

namespace App\Service;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierMiniOutput;
use App\Dto\Client\ChantierClientOutput;
use App\Dto\Chantier\ChantierPosteOutput;
use App\Dto\Chantier\ChantierDetailOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Entity\Chantier;

class ChantierService
{
    public function __construct(
        private ChantierRepository $chantierRepository
    ) {}


    /**
     * Liste les chantiers démarrés, à venir, terminés
     */
    public function list(): array //ChantierListOutput
    {
        $today = new \DateTime();
        $chantiers = $this->chantierRepository->findAll();

        $result = [
            'aVenir' => [],
            'demarres' => [],
            'termines' => [],
        ];

        foreach ($chantiers as $chantier) {

            $output = $this->mapToListItem($chantier);

            if ($this->isTermine($chantier)) {
                $result['termines'][] = $output;
            } elseif ($this->isDemarre($chantier)) {
                $result['demarres'][] = $output;
            } else {
                $result['aVenir'][] = $output;
            }
        }

        //Tris
       // Les Terminés : Du plus récent au plus ancien
       // Les Démarrés : Du plus ancien au plus récent
       // À Venir : Le plus proche arrive en premier
        usort($result['termines'], fn($a, $b) => $b->dateReception <=> $a->dateReception);
        usort($result['demarres'], fn($a, $b) => $a->dateDemarrage <=> $b->dateDemarrage);
        usort($result['aVenir'], fn($a, $b) => $a->dateDebutPrevue <=> $b->dateDebutPrevue);
        return $result;     
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


    private function isDemarre(Chantier $chantier): bool
    {
        $today = new \DateTimeImmutable('today'); // 'today' fixe l'heure à 00:00:00
        $dateDemarrage = $chantier->getDateDemarrage();

        return $dateDemarrage !== null 
            && $dateDemarrage <= $today 
            && $chantier->getDateReception() === null;
    }

    private function isTermine(Chantier $chantier): bool
    {
       $today = new \DateTimeImmutable();

        return $chantier->getDateReception()
            && $chantier->getDateReception() <= $today ;
    }

     private function isAVenir(Chantier $chantier): bool
    {
       $today = new \DateTimeImmutable();

       return $chantier->getDateDebut() !== null 
        && $chantier->getDateDebut() > $today 
        && $chantier->getDateDemarrage() === null;

    }

    /**
     * Mapping  pour la liste des démarrés, à venir, archivés
     */
    private function mapToListItem(Chantier $chantier): ChantierMiniOutput
    {
        $mini = new ChantierMiniOutput();

        $mini->id = $chantier->getId();
        $mini->nomClient = $chantier->getClient()?->getNom();
        $mini->ville = $chantier->getVille();
        $mini->dateDebutPrevue = $chantier->getDateDebutPrevue();
        $mini->dateDemarrage = $chantier->getDateDemarrage();
        $mini->dateReception = $chantier->getDateReception();
        $mini->dateFin = $chantier->getDateFin();

        return $mini;    

    }

}