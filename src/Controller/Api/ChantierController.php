<?php

namespace App\Controller\Api;

use App\Repository\ChantierRepository;
use App\Dto\Chantier\ChantierListOutput;
use App\Dto\Chantier\ChantierMiniOutput;
use App\Dto\Chantier\ChantierDetailOutput;
use App\Dto\Client\ClientDetailOutput;
use App\Dto\Chantier\ChantierPosteOutput;
use App\Dto\Chantier\ChantierEtapeOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api2/chantiers')]
class ChantierController extends AbstractController
{
    public function __construct(private ChantierRepository $repository) {}

    #[Route('/list', name: 'chantiers_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $data = new ChantierListOutput();

        $result = $this->repository->findChantiersParEtat();

        foreach ($result['demarres'] as $c) {
            $mini = new ChantierMiniOutput();
            $mini->id = $c->getId();
            $mini->adresse = $c->getAdresse();
            $mini->dateDemarrage = $c->getDateDemarrage();
            $mini->dateFin = $c->getDateFin();
            $data->demarres[] = $mini;
        }

        foreach ($result['aVenir'] as $c) {
            $mini = new ChantierMiniOutput();
            //$mini->id = $c->getId();
            $mini->adresse = $c->getAdresse();
            $mini->adresse = $c->getAdresse();
            $mini->dateDemarrage = $c->getDateDemarrage();
            $mini->dateFin = $c->getDateFin();
            $data->aVenir[] = $mini;
        }

        foreach ($result['termines'] as $c) {
            $mini = new ChantierMiniOutput();
            $mini->id = $c->getId();
            $mini->adresse = $c->getAdresse();
            $mini->dateDemarrage = $c->getDateDemarrage();
            $mini->dateFin = $c->getDateFin();
            $data->termines[] = $mini;
        }

        return $this->json($data);
    }
    
    #[Route('/show/{id}', name: 'chantier_show', methods: ['GET'])]
    public function detail(int $id): JsonResponse
    {
        $chantier = $this->repository->find($id);
        if (!$chantier) {
            return $this->json(['error' => 'Chantier non trouvé'], 404);
        }

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

        // Équipe : juste le nom
        if ($chantier->getEquipe()) {
            $dto->equipe = $chantier->getEquipe()->getNom();
        }

        if ($chantier->getClient()) {
            $clientDto = new ClientDetailOutput();
            //$clientDto->id = $chantier->getClient()->getId();
            $clientDto->nom = $chantier->getClient()->getNom();
            $clientDto->prenom = $chantier->getClient()->getPrenom();
            $clientDto->prenom = $chantier->getClient()->getTelephone();
            $clientDto->prenom = $chantier->getClient()->getMail();
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

        return $this->json($dto);
    }
}
