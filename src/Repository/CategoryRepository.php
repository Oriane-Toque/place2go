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
     * Return top 6 categories with events score
     *
     * @return Array top 6 categories
     */
    public function findTopCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->select('count(e) AS nbrEvents')
            ->addSelect('c')
            ->join('c.events', 'e')
            ->groupBy('c.id')
            ->orderBy('nbrEvents', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }
}
