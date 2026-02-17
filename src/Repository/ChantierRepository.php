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
    /*  Ne sert plus - refactoring dans ChantierService: list
    public function findChantiersParEtat(): array
    {
        $today = new \DateTime();

        // chantiers démarrés
        $demarres = $this->createQueryBuilder('c')
            ->where('c.dateDemarrage IS NOT NULL')
            ->andWhere('c.dateDemarrage <= :today')
            ->andWhere('c.dateReception IS NULL')
            ->setParameter('today', $today->format('Y-m-d'))
            ->orderBy('c.dateDemarrage', 'ASC')
            ->getQuery()
            ->getResult();


        // À venir
        $aVenir = $this->createQueryBuilder('c')
            ->where('c.dateDebutPrevue > :today')
            ->andWhere('c.dateDemarrage IS NULL')
            ->setParameter('today', $today->format('Y-m-d'))
            ->orderBy('c.dateDebutPrevue', 'ASC')
            ->getQuery()
            ->getResult();

       // Terminés : dateReception <= today
        $termines = $this->createQueryBuilder('c')
            ->where('c.dateReception IS NOT NULL')
            ->andWhere('c.dateReception <= :today')
            ->setParameter('today', $today->format('Y-m-d'))
            ->orderBy('c.dateReception', 'DESC')
            ->getQuery()
            ->getResult();

        return [
            'demarres' => $demarres,
            'aVenir' => $aVenir,
            'termines' => $termines,
        ];
    }*/



    //    /**
    //     * @return Chantier[] Returns an array of Chantier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Chantier
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
