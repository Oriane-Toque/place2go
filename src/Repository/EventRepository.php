<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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
     * Retrieve all the cities (but still in DESC order)
     *
     * @return Array all the cities
     */
    public function findAllCities(): array
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

    /**
     * Récupère les sorties en lien avec une recherche
     * 
     * @return Event[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('e')
            ->select('e', 'c')
            ->join('e.categories', 'c')   
        ;


        if(!empty($search->q)) {
            $query = $query
                ->andWhere('e.address LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }


        $query = $query
                ->andWhere('e.event_date > CURRENT_TIMESTAMP()')
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
		public function findEventsByCategory(int $category, string $city = null): array {

				$query = $this->createQueryBuilder('e')
				->join('e.categories', 'c')
				->where('c.id = :categoryId');
				if(null !== $city) {
					$query = $query->andWhere('e.city = :city')
					->setParameter('city', $city);
				}
				$query= $query->setParameter('categoryId', $category)
				->orderBy('e.event_date', 'DESC');
				
				return $query->getQuery()->getResult();
		}
}
