<?php

namespace App\Repository;

use App\Entity\IdentiteSociete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdentiteSociete|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentiteSociete|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentiteSociete[]    findAll()
 * @method IdentiteSociete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentiteSocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdentiteSociete::class);
    }

    // /**
    //  * @return IdentiteSociete[] Returns an array of IdentiteSociete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IdentiteSociete
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
