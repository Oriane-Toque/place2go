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
     * Find a friendship relation between two users
     * 
     * @param User $sender
     * @param User $receiver
     *
    */
    public function get(User $sender, User $receiver)
    {
        $friendship = $this->friendshipRepository->findOneBy(['sender' => $sender, 'receiver' => $receiver]);

        if (null === $friendship)
        {
            $friendship = $this->friendshipRepository->findOneBy(['sender' => $receiver, 'receiver' => $sender]);
        }
        return $friendship;
    }

    /**
     * Find all friendship relation between from a user
     * 
     * @param User $sender
     * @param User $receiver
     *
    */
    public function getAll(User $sender, User $receiver)
    {
        $friendship = $this->friendshipRepository->findBy(['sender' => $sender, 'receiver' => $receiver]);

        if (null === $friendship)
        {
            $friendship = $this->friendshipRepository->findBy(['sender' => $receiver, 'receiver' => $sender]);
        }
        return $friendship;
    }

}