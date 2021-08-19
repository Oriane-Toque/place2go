<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * Retrieve the top five categories with the most entries (DESC)
     *
     * @param Int $limit
     * @return Array the five categories
     */
    public function findAllCategories()
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'e')
            ->join('c.events', 'e')
            ->getQuery()
            ->getResult();
    }
}
