<?php

namespace App\Repository;

use App\Entity\Cabinet;
use App\Entity\Drawer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Drawer>
 */
class DrawerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drawer::class);
    }

    public function findEmptyDrawersForCabinet(Cabinet $cabinet): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.cabinet = :cabinet')
            ->setParameter('cabinet', $cabinet)
            ->leftJoin('d.items', 'i')
            ->groupBy('d.id')
            ->having('COUNT(i.id) = 0')
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Drawer[] Returns an array of Drawer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Drawer
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
