<?php

namespace App\Service;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierMiniOutput;
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
    public function getDetail(int $id): ChantierDetailOutput
    {
        $chantier = $this->chantierRepository->find($id);

        if (!$chantier) {
            throw new \RuntimeException('Chantier introuvable');
        }

        return $this->mapToDetailOutput($chantier);
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

    /**
     * Mapping vers ton DTO existant
     */
    private function mapToDetailOutput(Chantier $chantier): ChantierDetailOutput
    {
        $output = new ChantierDetailOutput();

        $output->id = $chantier->getId();
        $output->adresse = $chantier->getAdresse();
        $output->copos = $chantier->getCopos();
        $output->ville = $chantier->getVille();
        $output->dateDebutPrevue = $chantier->getDateDebutPrevue()?->format('Y-m-d');
        $output->surfacePlancher = $chantier->getSurfacePlancher();
        $output->surfaceHabitable = $chantier->getSurfaceHabitable();
        $output->distanceDepot = $chantier->getDistanceDepot();
        $output->tempsTrajet = $chantier->getTempsTrajet();
        $output->coefficient = $chantier->getCoefficient();
        $output->alerte = $chantier->isAlerte();

        $output->postes = [];

        foreach ($chantier->getChantierPostes() as $chantierPoste) {

            $posteOutput = new ChantierPosteOutput();

            $posteOutput->posteId = $chantierPoste->getPoste()->getId();
            $posteOutput->posteNom = $chantierPoste->getPoste()->getNom();
            $posteOutput->montantHT = $chantierPoste->getMontantHT();
            $posteOutput->montantTTC = $chantierPoste->getMontantTTC();
            $posteOutput->montantFournitures = $chantierPoste->getMontantFournitures();
            $posteOutput->nbJoursTravailles = $chantierPoste->getNbJoursTravailles();
            $posteOutput->montantPrestataire = $chantierPoste->getMontantPrestataire();

            $output->postes[] = $posteOutput;
        }

        return $output;
    }

}