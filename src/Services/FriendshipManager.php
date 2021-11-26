<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\Friendship;
use App\Entity\User;
use App\Repository\FriendshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class FriendshipManager
{
    private $mailer;
    private $entityManager;
    private $friendshipRepository;

    public function __construct(FriendshipRepository $friendshipRepository, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->friendshipRepository = $friendshipRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
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
        
        if ($friendship === null) {
            return false;
        }

        $this->entityManager->remove($friendship);
        $this->entityManager->flush();

        return true;
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

        if (null === $friendship) {
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

        if (null === $friendship) {
            $friendship = $this->friendshipRepository->findBy(['sender' => $friend, 'receiver' => $user]);
        }
        return $friendship;
    }

    /**
     * Notify all friends of an user
     *
     * @param User $user
     * @param Event $event
     *
     */
    public function eventAllFriendsNotifier(User $user, Event $event)
    {
        // Get all friends
        $friends = $this->friendshipRepository->findAllFriends($user);

        // Init the email to send
        $email = (new TemplatedEmail())
            ->from(new Address('checkmyapplications@gmail.com', 'Place 2 Go Emailer'))
            ->subject('Nouvelle sortie proposée par ' . $user->getNickname())
            ->htmlTemplate('event/friends_notifier_email.html.twig')
            ->context([
                'user' => $user,
                'event' => $event,
            ]);

        if (count($friends) == 0) {
            return 'Aucune notification envoyée';
        }

        // Send email to all friends
        foreach ($friends as $friend) {
            $email->to($friend->getEmail());

            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $e->getMessage();
            }
        }

        return true;
    }
}
