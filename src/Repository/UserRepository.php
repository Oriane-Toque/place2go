<?php

namespace App\Repository;

use App\Entity\Friendship;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Get count of all users
     *
     * @return Int
     */
    public function getTotalUsers():int
    {
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return (int)$result;
    }

    /**
     * Get count of all active users
     *
     * @return Int
     */
    public function getTotalActiveUsers():int
    {
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->where('u.isActive = true')
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return (int)$result;
    }

    /**
     * Find all users with role
     *
     * @param String $role
     * @return Array
     */
    public function findUsersByRole($role):array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.roles = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Return search results : all friends by keywords
     *
     * @param string $friend
     */
    public function searchFriends(string $friend = null, User $user)
    {
        $query = $this
                ->createQueryBuilder('u')
                ->select('u')
                ->where('u.id != :user')
                ->setParameter(':user', $user->getId())
                ;

                if($friend) {
                    $query = $query
                    ->andWhere('u.nickname LIKE :friend')
                    ->orWhere('u.firstname LIKE :friend')
                    ->orWhere('u.lastname LIKE :friend')
                    ->setParameter(':friend', $friend.'%')
                    ;
                }

        return $query->getQuery()->getResult();
    }

}
