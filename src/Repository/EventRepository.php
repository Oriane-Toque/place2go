<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Retrieve the top five cities with the most entries (DESC)
     *
     * @param Int $limit
     * @return Array the five cities
     */
    public function findPopularCities(int $limit)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT e.city, COUNT(e.city) AS count
            FROM App\Entity\Event e
            GROUP BY e.city
            ORDER BY count DESC'
        )->setMaxResults($limit);
        return $query->getResult();
    }

     /**
     * Retrieve all the cities (but still in DESC order)
     *
     * @return Array all the cities
     */
    public function findAllCities()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT e.city, COUNT(e.city) AS count
            FROM App\Entity\Event e
            GROUP BY e.city
            ORDER BY count DESC'
        );
        return $query->getResult();
    }
   
    /**
     * Recover the last three events of the organizer order by event date (DESC)
     *
     * @param Int $userId
     * @return Array tableau d'objets, les 3 dernières sorties proposées
     */
    public function findLastThreeAuthorEvents(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.author = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('e.event_date', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recover the last three exits of the user order by event date (DESC)
     *
     * @param Int $userId
     * @return Array tableau d'objets, les 3 dernières sorties auxquels l'utilisateur participe
     */
    public function findLastThreeAttendantEvents(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('App\Entity\Attendant', 'a', 'WITH', 'e.id = a.event')
            ->where('a.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('e.event_date', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    /*
			public function findByExampleField($value)
			{
					return $this->createQueryBuilder('e')
							->andWhere('e.exampleField = :val')
							->setParameter('val', $value)
							->orderBy('e.id', 'ASC')
							->setMaxResults(10)
							->getQuery()
							->getResult()
					;
			}
			*/

    /*
			public function findOneBySomeField($value): ?Event
			{
					return $this->createQueryBuilder('e')
							->andWhere('e.exampleField = :val')
							->setParameter('val', $value)
							->getQuery()
							->getOneOrNullResult()
					;
			}
			*/
}
