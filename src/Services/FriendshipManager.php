<?php

namespace App\Services;

use App\Entity\Friendship;
use App\Entity\User;
use App\Repository\FriendshipRepository;
use Doctrine\ORM\EntityManagerInterface;

class FriendshipManager
{
    private $entityManager;
    private $friendshipRepository;

    public function __construct(FriendshipRepository $friendshipRepository, EntityManagerInterface $entityManager)
    {
        $this->friendshipRepository = $friendshipRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Create a friendship between two users
     * 
     * @param User $sender
     * @param User $receiver
     *
    */
    public function create(User $sender, User $receiver)
    {
        $friendship = new Friendship();
        $friendship->setSender($sender);
        $friendship->setReceiver($receiver);

        $sender->addFriends($friendship);
        $receiver->addFriendsWithMe($friendship);

        $this->entityManager->persist($friendship);
        $this->entityManager->flush();
    }

    /**
     * Delete a friendship between two users
     * 
     * @param User $user
     * @param User $friend
     *
    */
    public function delete(User $user, User $friend)
    {
        $friendship = $this->get($user, $friend);

        $this->entityManager->remove($friendship);
        $this->entityManager->flush();
    }

    /**
     * Find a friendship relation between two users
     * 
     * @param User $user
     * @param User $friend
     *
    */
    public function get(User $user, User $friend)
    {
        $friendship = $this->friendshipRepository->findOneBy(['sender' => $user, 'receiver' => $friend]);

        if (null === $friendship)
        {
            $friendship = $this->friendshipRepository->findOneBy(['sender' => $friend, 'receiver' => $user]);
        }
        return $friendship;
    }

    /**
     * Find all friendship relation between from a user
     * 
     * @param User $user
     * @param User $friend
     *
    */
    public function getAll(User $user, User $friend)
    {
        $friendship = $this->friendshipRepository->findBy(['sender' => $user, 'receiver' => $friend]);

        if (null === $friendship)
        {
            $friendship = $this->friendshipRepository->findBy(['sender' => $friend, 'receiver' => $user]);
        }
        return $friendship;
    }

}