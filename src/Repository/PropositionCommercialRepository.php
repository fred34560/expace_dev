<?php

namespace App\Repository;

use App\Entity\PropositionCommercial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropositionCommercial|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropositionCommercial|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropositionCommercial[]    findAll()
 * @method PropositionCommercial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionCommercialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropositionCommercial::class);
    }

    // /**
    //  * @return PropositionCommercial[] Returns an array of PropositionCommercial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PropositionCommercial
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
