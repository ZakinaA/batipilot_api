<?php

namespace App\Service;

use App\Dto\Chantier\Input\CreateChantierInput;
use App\Dto\Chantier\Input\UpsertChantierPostesInput;
use App\Dto\Chantier\Input\UpsertChantierEtapesInput;
use App\Entity\Chantier;
use App\Entity\ChantierPoste;
use App\Entity\ChantierEtape;
use App\Exception\ApiException;
use App\Repository\ChantierPosteRepository;
use App\Repository\ChantierEtapeRepository;
use App\Repository\ClientRepository;
use App\Repository\EquipeRepository;
use App\Repository\PosteRepository;
use App\Repository\EtapeRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChantierWriteService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ClientRepository $clientRepo,
        private EquipeRepository $equipeRepo,
        private PosteRepository $posteRepo,
        private EtapeRepository $etapeRepo,
        private ChantierPosteRepository $chantierPosteRepo,
        private ChantierEtapeRepository $chantierEtapeRepo,
    ) {}

    public function createChantier(CreateChantierInput $in): int
    {
        $client = $this->clientRepo->find($in->clientId);
        if (!$client) {
            throw new ApiException('Client introuvable', 'CLIENT_NOT_FOUND', 404);
        }

        $equipe = $this->equipeRepo->find($in->equipeId);
        if (!$equipe) {
            throw new ApiException('Équipe introuvable', 'EQUIPE_NOT_FOUND', 404);
        }

        $chantier = new Chantier();
        $chantier->setClient($client);
        $chantier->setEquipe($equipe);

        $chantier->setAdresse($in->adresse);
        $chantier->setCopos($in->copos);
        $chantier->setVille($in->ville);

        // dates (string -> DateTime)
        $chantier->setDateDebutPrevue(new \DateTime($in->dateDebutPrevue));
        $chantier->setDateFin($in->dateFin ? new \DateTime($in->dateFin) : null);

        $chantier->setSurfacePlancher($in->surfacePlancher);
        $chantier->setSurfaceHabitable($in->surfaceHabitable);
        $chantier->setDistanceDepot($in->distanceDepot);
        $chantier->setTempsTrajet($in->tempsTrajet);
        $chantier->setCoefficient($in->coefficient);
        $chantier->setAlerte($in->alerte);

        // archive obligatoire dans ton entité
        $chantier->setArchive(0);

        $this->em->persist($chantier);
        $this->em->flush();

        return (int) $chantier->getId();
    }

    public function upsertPostes(Chantier $chantier, UpsertChantierPostesInput $in): int
    {
        $existing = $this->chantierPosteRepo->findByChantierIndexedByPoste($chantier);

        $count = 0;
        foreach ($in->items as $item) {
            $poste = $this->posteRepo->find($item->posteId);
            if (!$poste) {
                throw new ApiException("Poste introuvable (id={$item->posteId})", 'POSTE_NOT_FOUND', 404);
            }

            $cp = $existing[$item->posteId] ?? null;
            if (!$cp) {
                $cp = new ChantierPoste();
                $cp->setChantier($chantier);
                $cp->setPoste($poste);
                $this->em->persist($cp);
                $existing[$item->posteId] = $cp;
            }

            $cp->setMontantHT($item->montantHT);
            $cp->setMontantTTC($item->montantTTC);
            $cp->setMontantFournitures($item->montantFournitures);
            $cp->setNbJoursTravailles($item->nbJoursTravailles);
            $cp->setMontantPrestataire($item->montantPrestataire);
            $cp->setNomPrestataire($item->nomPrestataire);

            $count++;
        }

        $this->em->flush();
        return $count;
    }

    public function upsertEtapes(Chantier $chantier, UpsertChantierEtapesInput $in): int
    {
        $existing = $this->chantierEtapeRepo->findByChantierIndexedByEtape($chantier);

        $count = 0;
        foreach ($in->items as $item) {
            $etape = $this->etapeRepo->find($item->etapeId);
            if (!$etape) {
                throw new ApiException("Étape introuvable (id={$item->etapeId})", 'ETAPE_NOT_FOUND', 404);
            }

            $ce = $existing[$item->etapeId] ?? null;
            if (!$ce) {
                $ce = new ChantierEtape();
                $ce->setChantier($chantier);
                $ce->setEtape($etape);
                $this->em->persist($ce);
                $existing[$item->etapeId] = $ce;
            }

            // On laisse la liberté, mais tu peux renforcer selon EtapeFormat.libelle
            $ce->setValText($item->valText);
            $ce->setValBoolean($item->valBoolean);
            $ce->setValInteger($item->valInteger);
            $ce->setValFloat($item->valFloat);

            $ce->setValDate($item->valDate ? new \DateTime($item->valDate) : null);

            if ($item->valDateHeure) {
                // attends idéalement un ISO8601
                $ce->setValDateHeure(new \DateTime($item->valDateHeure));
            } else {
                $ce->setValDateHeure(null);
            }

            $count++;
        }

        $this->em->flush();
        return $count;
    }
}