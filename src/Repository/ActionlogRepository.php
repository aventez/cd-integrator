<?php

namespace App\Repository;

use App\Entity\Actionlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Actionlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actionlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actionlog[]    findAll()
 * @method Actionlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actionlog::class);
    }

    // /**
    //  * @return Actionlog[] Returns an array of Actionlog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Actionlog
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
