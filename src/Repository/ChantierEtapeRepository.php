<?php

namespace App\Repository;

use App\Entity\Chantier;
use App\Entity\ChantierEtape;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChantierEtapeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChantierEtape::class);
    }

    /** @return array<int, ChantierEtape> [etapeId => ChantierEtape] */
    public function findByChantierIndexedByEtape(Chantier $chantier): array
    {
        $rows = $this->createQueryBuilder('ce')
            ->leftJoin('ce.etape', 'e')->addSelect('e')
            ->andWhere('ce.chantier = :c')->setParameter('c', $chantier)
            ->getQuery()
            ->getResult();

        $out = [];
        foreach ($rows as $ce) {
            $etapeId = $ce->getEtape()?->getId();
            if ($etapeId) {
                $out[(int) $etapeId] = $ce;
            }
        }

        return $out;
    }
}