<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ["BASIC_ACCESS", "USER_ACCESS", "ADMIN_ACCESS"])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /**@var UserInterface $user */
        $user = $token->getUser();

        /**@var \App\Entity\User $userSubject */
        $userSubject = $subject;
        
        // if current user does not exist, return false
        if (null === $user) {
            return false;
        }
        
        switch ($attribute) {
            case "BASIC_ACCESS":
                
                if (null === $userSubject) {
                    return false;
                }

                return true;
                
                break;
                
                case "USER_ACCESS":
                
                if (!$user === $userSubject) {
                    return false;
                }
                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$userSubject->isVerified() === true) {
                    return false;
                }
                if (!$userSubject->getIsActive() === true) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_USER') || !$this->security->getUser()->getRoles('ROLE_ADMIN')) {
                    return false;
                }
                return true;

            break;

            case "ADMIN_ACCESS":

                if (!$user === $userSubject) {
                    return false;
                }
                if (!$user instanceof UserInterface) {
                    return false;
                }
                if (!$userSubject->isVerified() === true) {
                    return false;
                }
                if (!$userSubject->getIsActive() === true) {
                    return false;
                }
                if (!$this->security->isGranted('ROLE_ADMIN')) {
                    return false;
                }

                return true;

            break;
        }
            
        return false;
    }
}
