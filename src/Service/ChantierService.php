<?php

namespace App\Service;

use App\Dto\Chantier\Output\ChantierKpiOutput;
use App\Dto\Chantier\Output\ChantierListParEtatOutput;
use App\Dto\Chantier\Output\ChantierOverviewOutput;
use App\Dto\Chantier\Output\ChantierSuiviOutput;
use App\Entity\Chantier;
use App\Service\Chantier\Query\ChantierKpiQuery;
use App\Service\Chantier\Query\ChantierListQuery;
use App\Service\Chantier\Query\ChantierOverviewQuery;
use App\Service\Chantier\Query\ChantierSuiviQuery;

class ChantierService
{
    public function __construct(
        private ChantierListQuery $listQuery,
        private ChantierOverviewQuery $overviewQuery,
        private ChantierKpiQuery $kpiQuery,
        private ChantierSuiviQuery $suiviQuery
    ) {}

    /* Liste les chantiers démarrés, à venir et terminés
     * Renvoie ChantierListParEtatOutput qui est un array des 3 listes
     */
    public function list(): ChantierListParEtatOutput
    {
         return $this->listQuery->listParEtat();
    }


     /**
     * Retourne les informations générales du chantier + totalHT
     */
    public function showOverview(Chantier $chantier): ChantierOverviewOutput
    {
        return $this->overviewQuery->overview($chantier);
    }


    /**
     * Retourne les informations financières du chantier
     */
    public function showKpi(Chantier $chantier): ChantierKpiOutput
    {   
        return $this->kpiQuery->kpi($chantier);
    }

    /**
     * Retourne les étapes par poste d'un chantier
     */
    public function showSuivi(Chantier $chantier): ChantierSuiviOutput
    { 
        return $this->suiviQuery->suivi($chantier);
    }
}