<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Retrieve all the categories
     *
     * @return Array all the categories
     */
    public function findTopCategories()
    {

			$sql='
			SELECT category.*, COUNT(*) as nbrEvents
			FROM category
			INNER JOIN event_category
			ON event_category.category_id = category.id
			GROUP BY category_id
			ORDER BY nbrEvents DESC
			LIMIT 6';

			$em = $this->getEntityManager();
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->executeQuery();
			return $stmt->fetchAll();
    }
}
