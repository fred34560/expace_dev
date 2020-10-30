<?php

namespace App\Repository;

use App\Entity\StatsDevelop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatsDevelop|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatsDevelop|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatsDevelop[]    findAll()
 * @method StatsDevelop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsDevelopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatsDevelop::class);
    }

    // /**
    //  * @return StatsDevelop[] Returns an array of StatsDevelop objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatsDevelop
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function dureeCode()
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.Duree) as dureeTotal')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function nbreApp() {
  
        return $this->createQueryBuilder('i')
                    ->select('COUNT(i)')
                    ->getQuery()
                    ->getSingleScalarResult(); 
    }
    public function nbreRecompense() {
  
        return $this->createQueryBuilder('j')
                    ->andWhere('j.recompense = 1')
                    ->select('COUNT(j)')
                    ->getQuery()
                    ->getSingleScalarResult(); 
    }
}
