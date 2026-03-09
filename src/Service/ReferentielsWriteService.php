<?php

namespace App\Service;

use App\Dto\Referentiels\Input\CreateOrUpdatePosteInput;
use App\Dto\Referentiels\Input\CreateOrUpdateEtapeInput;
use App\Entity\Poste;
use App\Entity\Etape;
use App\Exception\ApiException;
use App\Repository\PosteRepository;
use App\Repository\EtapeFormatRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReferentielsWriteService
{
    public function __construct(
        private EntityManagerInterface $em,
        private PosteRepository $posteRepo,
        private EtapeFormatRepository $etapeFormatRepo,
    ) {}

    public function createPoste(CreateOrUpdatePosteInput $in): int
    {
        $poste = new Poste();
        $this->applyPosteData($poste, $in);

        $this->em->persist($poste);
        $this->em->flush();

        return (int) $poste->getId();
    }

    public function updatePoste(Poste $poste, CreateOrUpdatePosteInput $in): void
    {
        $this->applyPosteData($poste, $in);
        $this->em->flush();
    }

    private function applyPosteData(Poste $poste, CreateOrUpdatePosteInput $in): void
    {
        //dd($in);
        $poste->setLibelle(trim($in->libelle));
        $poste->setTva($in->tva);
        $poste->setOrdre($in->ordre);
        $poste->setArchive($in->archive ? 1 : 0);
    }

    public function createEtape(CreateOrUpdateEtapeInput $in): int
    {
        $poste = $this->posteRepo->find($in->posteId);
        if (!$poste) {
            throw new ApiException('Poste introuvable', 'POSTE_NOT_FOUND', 404);
        }

        $format = $this->etapeFormatRepo->find($in->etapeFormatId);
        if (!$format) {
            throw new ApiException('Format d’étape introuvable', 'ETAPE_FORMAT_NOT_FOUND', 404);
        }

        $etape = new Etape();
        $this->applyEtapeData($etape, $poste, $format, $in);

        $this->em->persist($etape);
        $this->em->flush();

        return (int) $etape->getId();
    }

    public function updateEtape(Etape $etape, CreateOrUpdateEtapeInput $in): void
    {
        $poste = $this->posteRepo->find($in->posteId);
        if (!$poste) {
            throw new ApiException('Poste introuvable', 'POSTE_NOT_FOUND', 404);
        }

        $format = $this->etapeFormatRepo->find($in->etapeFormatId);
        if (!$format) {
            throw new ApiException('Format d’étape introuvable', 'ETAPE_FORMAT_NOT_FOUND', 404);
        }

        $this->applyEtapeData($etape, $poste, $format, $in);
        $this->em->flush();
    }

    private function applyEtapeData(Etape $etape, Poste $poste, $format, CreateOrUpdateEtapeInput $in): void
    {
        $etape->setLibelle(trim($in->libelle));
        $etape->setPoste($poste);
        $etape->setEtapeFormat($format);
        $etape->setArchive($in->archive ? 1 : 0);
    }
}