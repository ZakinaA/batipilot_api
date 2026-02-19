<?php

namespace App\Repository;

use App\Entity\Chantier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chantier>
 */
class ChantierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chantier::class);
    }

    public function findAvecClientEtPostes(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.client', 'cl')->addSelect('cl')
            ->leftJoin('c.chantierPostes', 'cp')->addSelect('cp')
            ->getQuery()
            ->getResult();
    }

}
