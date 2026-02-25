<?php

namespace App\Repository;

use App\Entity\Chantier;
use App\Entity\ChantierPoste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChantierPoste>
 */
class ChantierPosteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChantierPoste::class);
    }

     /** @return array<int, ChantierPoste> [posteId => ChantierPoste] */
    public function findByChantierIndexedByPoste(Chantier $chantier): array
    {
        $rows = $this->createQueryBuilder('cp')
            ->leftJoin('cp.poste', 'p')->addSelect('p')
            ->andWhere('cp.chantier = :c')->setParameter('c', $chantier)
            ->getQuery()
            ->getResult();

        $out = [];
        foreach ($rows as $cp) {
            $posteId = $cp->getPoste()?->getId();
            if ($posteId) {
                $out[(int) $posteId] = $cp;
            }
        }

        return $out;
    }
}
