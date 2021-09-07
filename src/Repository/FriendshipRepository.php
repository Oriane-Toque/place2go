<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Friendship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Friendship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friendship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friendship[]    findAll()
 * @method Friendship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friendship::class);
    }


    // Return list of friends
    public function findAllFriends($userId)
    {
        $friends = [];

        // Find all users who accepted my requests
        $results = $this->createQueryBuilder('f')
            ->where('f.sender = :userId')
            ->andwhere('f.status = 1')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
        
        foreach ($results as $result) {
            $friends[] = $result->getReceiver();
        }

        // Find all users who requested me
        $results = $this->createQueryBuilder('f')
            ->where('f.receiver = :userId')
            ->andwhere('f.status = 1')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;

        foreach ($results as $result) {
            $friends[] = $result->getSender();
        }

        return $friends;
    }

    /**
     * Get count of friend request received
     *
     * @param Int $id
     * @return Int
     */
    public function getTotalFriendRequest(int $id): Int
    {
        $result = $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->where('f.receiver = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return (int)$result;
    }
}
