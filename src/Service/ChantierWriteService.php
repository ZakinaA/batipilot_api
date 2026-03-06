<?php

namespace App\Service;

use App\Dto\Chantier\Input\CreateChantierOverviewInput;
use App\Dto\Chantier\Input\UpsertChantierPostesInput;
use App\Dto\Chantier\Input\UpsertChantierEtapesInput;
use App\Entity\Chantier;
use App\Entity\ChantierPoste;
use App\Entity\ChantierEtape;
use App\Exception\ApiException;
use App\Repository\ChantierPosteRepository;
use App\Repository\ChantierEtapeRepository;
use App\Repository\EquipeRepository;
use App\Repository\PosteRepository;
use App\Repository\EtapeRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChantierWriteService
{
    public function __construct(
        private EntityManagerInterface $em,
        private EquipeRepository $equipeRepo,
        private PosteRepository $posteRepo,
        private EtapeRepository $etapeRepo,
        private ChantierPosteRepository $chantierPosteRepo,
        private ChantierEtapeRepository $chantierEtapeRepo,
    ) {}

    public function createChantier(CreateChantierOverviewInput $in): int
    {
        if (!$in->client || !$in->chantier) {
            throw new ApiException('Payload invalide', 'INVALID_PAYLOAD', 400);
        }

        $equipe = $this->equipeRepo->find($in->chantier->equipeId);
        if (!$equipe) {
            throw new ApiException('Équipe introuvable', 'EQUIPE_NOT_FOUND', 404);
        }

        $client = new \App\Entity\Client();
        $client->setNom(trim((string) $in->client->nom));
        $client->setPrenom($in->client->prenom ? trim($in->client->prenom) : null);
        $client->setTelephone($in->client->telephone ? trim($in->client->telephone) : null);
        $client->setMail($in->client->mail ? trim($in->client->mail) : null);

        $chantier = new Chantier();
        $chantier->setClient($client);
        $chantier->setEquipe($equipe);

        $chantier->setAdresse($in->chantier->adresse);
        $chantier->setCopos($in->chantier->copos);
        $chantier->setVille($in->chantier->ville);

        $chantier->setDateDemarrage(new \DateTime($in->chantier->dateDemarrage));
        $chantier->setDateReception($in->chantier->dateReception ? new \DateTime($in->chantier->dateReception) : null);
        //$chantier->setDateFin($in->chantier->dateFin ? new \DateTime($in->chantier->dateFin) : null);

        $chantier->setSurfacePlancher($in->chantier->surfacePlancher);
        $chantier->setSurfaceHabitable($in->chantier->surfaceHabitable);
        $chantier->setDistanceDepot($in->chantier->distanceDepot);
        $chantier->setTempsTrajet($in->chantier->tempsTrajet);
        $chantier->setCoefficient($in->chantier->coefficient);
        $chantier->setAlerte($in->chantier->alerte);

        $chantier->setArchive(0);

        $this->em->persist($client);
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