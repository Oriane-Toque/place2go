<?php

namespace App\Repository;

use App\Entity\Attendant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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

    public function findByUserEvent($user, $event)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
                        ->andWhere('a.event = :event')
            ->setParameters(new ArrayCollection([
                            new Parameter('user', $user),
                            new Parameter('event', $event)
                        ]))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByEvent($event)
    {
        return $this->createQueryBuilder('a')
                        ->select('u.id')
                        ->innerJoin('App\Entity\User', 'u', 'WITH', 'a.user = u.id')
                        ->where('a.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * Return all attendants to one event
     *
     * @return Array All users
     */
    public function findAttendantsByEvent($event)
    {
        return $this->createQueryBuilder('a')
            ->select('u')
            ->innerJoin('App\Entity\User', 'u', 'WITH', 'a.user = u.id')
            ->where('a.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }

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
