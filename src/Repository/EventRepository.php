<?php

namespace App\Repository;

use App\Entity\Event;
use App\Data\SearchData;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
     * Find All Query
     */
    public function findAllQuery(array $options = [])
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.isActive = 1');

        foreach ($options as $key => $value) {
            $qb->andWhere($key.' LIKE :value')
            ->setParameter('value', "%{$value}%");
        }
        
        return $qb->getQuery();
    }

    /**
     * Return the top six cities with events score (DESC)
     *
     * @return Array the top six cities
     */
    public function findTopCities(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT e.address, COUNT(e.address) AS nbrEvents
            FROM App\Entity\Event e
            GROUP BY e.address
            ORDER BY nbrEvents DESC'
        )->setMaxResults(6);
        return $query->getResult();
    }

    /**
     * Return the top six contributors with events score (DESC)
     *
     * @return Array the top six contributors
     */
    public function findTopContributors(): array
    {
        return $this->createQueryBuilder('e')
            ->select('count(e) AS nbrEvents')
            ->addSelect('u')
            ->innerJoin('App\Entity\User', 'u', 'WITH', 'e.author = u.id')
            ->groupBy('u.id')
            ->orderBy('nbrEvents', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get count of all events
     *
     * @return Int
     */
    public function getTotalEvents(): int
    {
        $result = $this->createQueryBuilder('e')
            ->select('COUNT(e)')
            ->getQuery()
            ->getSingleScalarResult();
        return (int)$result;
    }

    /**
     * Get count of events to come
     *
     * @return Int
     */
    public function getTotalEventsToCome(): int
    {
        $result = $this->createQueryBuilder('e')
            ->select('COUNT(e)')
            ->where('e.event_date > CURRENT_DATE()')
            ->andWhere('e.isActive = 1')
            ->getQuery()
            ->getSingleScalarResult();
        return (int)$result;
    }

    /**
     * Recover the last events of the organizer order by event date (DESC)
     *
     * @param Int $userId
     * @param Int $limit
     * @return Array tableau d'objets, les dernières sorties proposées
     */
    public function findLastAuthorEvents(int $userId, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e', 'user')
            ->join('e.author', 'user')
            ->where('e.author = :userId')
            ->andWhere('e.isActive = 1')
            ->setParameter('userId', $userId)
            ->orderBy('e.event_date', 'DESC');

        // Set a limit if variable is sent
        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * Recover the last exits of the user order by event date (DESC)
     *
     * @param Int $userId
     * @param Int $limit
     * @return Array tableau d'objets, les dernières sorties auxquels l'utilisateur participe
     */
    public function findLastAttendantEvents(int $userId, int $limit = null): array
    {
        $qb =  $this->createQueryBuilder('e')
            ->select('e', 'a')
            ->join('e.attendants', 'a')
            // ->innerJoin('App\Entity\Attendant', 'a', 'WITH', 'e.id = a.event')
            ->where('a.user = :userId')
            ->andWhere('e.isActive = 1')
            ->setParameter('userId', $userId)
            ->orderBy('e.event_date', 'DESC');

        // Set a limit if variable is sent
        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * Recover the next events of the organizer order by event date (DESC)
     *
     * @param Int $userId
     * @param Int $limit
     * @return Array tableau d'objets, les dernières sorties proposées
     */
    public function findNextAuthorEvents(int $userId, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.author = :userId')
            ->andWhere('e.event_date > CURRENT_DATE()')
            ->andWhere('e.isActive = 1')
            ->setParameter('userId', $userId)
            ->orderBy('e.event_date', 'DESC');

        // Set a limit if variable is sent
        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Recover the next exits of the user order by event date (DESC)
     *
     * @param Int $userId
     * @param Int $limit
     * @return Array tableau d'objets, les dernières sorties auxquels l'utilisateur participe
     */
    public function findNextAttendantEvents(int $userId, int $limit = null): array
    {
        $qb =  $this->createQueryBuilder('e')
            ->innerJoin('App\Entity\Attendant', 'a', 'WITH', 'e.id = a.event')
            ->where('a.user = :userId')
            ->andWhere('e.event_date > CURRENT_DATE()')
            ->andWhere('e.isActive = 1')
            ->setParameter('userId', $userId)
            ->orderBy('e.event_date', 'DESC');

        // Set a limit if variable is sent
        if (!is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupère les sorties en lien avec une recherche
     *
     * @return Event[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('e')
            ->select('e', 'c', 'user', 'a', 'r')
            ->where('e.isActive = 1')
            ->join('e.categories', 'c')
            ->join('e.author', 'user')
            ->leftJoin('e.attendants', 'a')
            ->leftJoin('e.reports', 'r')
        ;

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('e.address LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        $query = $query
            // sql query to get event of the day
            ->andWhere('e.event_date > CURRENT_DATE()')
            ->orderBy('e.event_date', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * Return all events by category & city params
     *
     * @param Int $category
     * @param String $city city's user
     * @return Array tableau d'objets, liste de sorties
     */
    public function findEventsByCategory(Category $category, string $city = null): array
    {
        $query = $this->createQueryBuilder('e')
            ->join('e.categories', 'c')
            ->where('c.id = :categoryId')
            ->andWhere('e.isActive = 1');

        if (null !== $city) {
            $query = $query->andWhere('e.address LIKE :city')
                ->setParameter('city', "%{$city}%");
        }

        $query = $query->setParameter('categoryId', $category)
            ->orderBy('e.event_date', 'DESC');

        return $query->getQuery()->getResult();
    }

    /**
     * Return 6 random events
     *
     * @param String $city (option) ville enregistrée sur le compte utilisateur
     * @return Array liste des sorties
     */
    public function findRandEvents(string $city = null): array
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.isActive = 1');

        if ($city) {
            $query = $query->where('e.address LIKE :city')
                ->setParameter('city', "%{$city}%");
        }

        $query = $query->orderBy('RAND()')
            ->addOrderBy('e.event_date', 'DESC')
            ->setMaxResults(6);

        return $query->getQuery()->getResult();
    }

    /**
     * Return events data for graph
     *
     * @param Int $year (option) year
     * @return Array events count by month
     */
    public function getCountEventsByMonth(int $year = null): array
    {
        $query = $this->createQueryBuilder('e')
            ->select('MONTH(e.event_date) AS month, COUNT(e) AS count');

        if ($year) {
            $query->where('YEAR(e.event_date) = :year')
                ->setParameter('year', $year);
        } else {
            $query->where('YEAR(e.event_date) = YEAR(CURRENT_DATE())');
        }

        $query->groupBy('month');
        $query->orderBy('month', 'ASC');

        return $query->getQuery()->getResult();
    }
}
