<?php

namespace App\Service\Chantier\Query;

use App\Dto\Chantier\ChantierSuiviOutput;
use App\Dto\Chantier\ChantierPosteEtapesOutput;
use App\Dto\Etape\EtapeValueOutput;
use App\Entity\Chantier;
use App\Entity\ChantierEtape;
use App\Entity\Etape;

class ChantierSuiviQuery
{
    public function __construct(
        private ChantierHeaderBuilder $headerBuilder
    ) {}

    public function suivi(Chantier $chantier): ChantierSuiviOutput
    {
        $dto = new ChantierSuiviOutput();
        $dto->header = $this->headerBuilder->build($chantier);

        $index = $this->indexChantierEtapesByEtapeId($chantier);

        foreach ($chantier->getChantierPostes() as $cp) {
            $posteDto = new ChantierPosteEtapesOutput();
            $posteDto->id = (int) $cp->getId();
            $posteDto->libelle = $cp->getPoste()?->getLibelle();
            $posteDto->montantHT = (float) ($cp->getMontantHT() ?? 0.0);
            $posteDto->montantTTC = (float) ($cp->getMontantTTC() ?? 0.0);

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
                $dto->rawValue = $b;
                $dto->displayValue = $b === null ? null : ($b ? 'Oui' : 'Non');
                break;

            case 'nombre entier':
                $i = $chantierEtape->getValInteger();
                $dto->rawValue = $i;
                $dto->displayValue = $i === null ? null : (string) $i;
                break;

            case 'nombre décimal':
            case 'nombre decimal':
                $f = $chantierEtape->getValFloat();
                $dto->rawValue = $f;
                $dto->displayValue = $f === null ? null : rtrim(rtrim(number_format($f, 2, ',', ' '), '0'), ',');
                break;

            case 'texte':
                $t = $chantierEtape->getValText();
                $dto->rawValue = $t;
                $dto->displayValue = ($t === null || trim($t) === '') ? null : $t;
                break;

            case 'date':
                $d = $chantierEtape->getValDate();
                $dto->rawValue = $d?->format('Y-m-d');
                $dto->displayValue = $d?->format('d/m/Y');
                break;

            case 'date et heure':
                $dt = $chantierEtape->getValDateHeure();
                $dto->rawValue = $dt?->format(\DateTimeInterface::ATOM);
                $dto->displayValue = $dt?->format('d/m/Y H:i');
                break;

            default:
                $dto->rawValue = null;
                $dto->displayValue = null;
        }

        return $dto;
    }
}