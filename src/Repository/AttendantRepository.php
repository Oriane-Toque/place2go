<?php

namespace App\Repository;

use App\Entity\Attendant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attendant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attendant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attendant[]    findAll()
 * @method Attendant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendant::class);
    }

    // /**
    //  * @return Attendant[] Returns an array of Attendant objects
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
    public function findOneBySomeField($value): ?Attendant
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
