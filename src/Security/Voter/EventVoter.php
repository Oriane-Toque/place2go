<?php

namespace App\Security\Voter;

use App\Repository\AttendantRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    private Security $security;
    private AttendantRepository $attendantRepository;

    public function __construct(Security $security, AttendantRepository $attendantRepository)
    {
        $this->security = $security;
        $this->attendantRepository = $attendantRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EVENT_SHOW', 'EVENT_CREATE', 'EVENT_EDIT', 'EVENT_DELETE', 'EVENT_JOIN', 'EVENT_LEAVE'])
            && $subject instanceof \App\Entity\Event;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /**@var UserInterface $user */
        $user = $token->getUser();
        /**@var \App\Entity\Event $event */
        $event = $subject;

        // If event does not exist, deny access
        if (null === $event) {
            return false;
        }


        switch ($attribute) {
            case 'EVENT_SHOW':

                return true;

                break;
            case 'EVENT_CREATE':

                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_USER')) {
                    return false;
                }

                return true;

                break;
            case 'EVENT_EDIT':

                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_USER')) {
                    return false;
                }
                // if user is not the author of the event, deny access
                if ($user !== $event->getAuthor()) {
                    return false;
                }

                return true;

                break;
            case 'EVENT_DELETE':

                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_USER')) {
                    return false;
                }
                // if user is not the author of the event, deny access
                if ($user !== $event->getAuthor()) {
                    return false;
                }

                return true;

                break;
            case 'EVENT_JOIN':

                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_USER')) {
                    return false;
                }
                // If user is the author of the event, deny access
                if ($user === $event->getAuthor()) {
                    return false;
                }
                // If user is already an attendant, deny access
                if ($this->attendantRepository->findOneBy(['event' => $event, 'user' => $user])) {
                    return false;
                }

                return true;

                break;

            case 'EVENT_LEAVE':

                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_USER')) {
                    return false;
                }
                // If user is the author of the event, deny access
                if ($user === $event->getAuthor()) {
                    return false;
                }
                // If user is not an attendant, deny access
                if (!$this->attendantRepository->findOneBy(['event' => $event, 'user' => $user])) {
                    return false;
                }
      
                return true;

                break;
        }
        return false;
    }
}
