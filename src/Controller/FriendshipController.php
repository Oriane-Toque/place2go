<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Friendship;
use App\Repository\FriendshipRepository;
use App\Services\FriendshipManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendshipController extends AbstractController
{
    
    /**
     * Send a friend request
     *
     * @Route("/profile/friends/{id<\d+>}/add", name="app_friend_add", methods={"GET"})
     * @isGranted("ROLE_USER")
     *
     * @param Request $request
     * @param User $friend
     * @param FriendshipRepository $friendshipRepository
     *
     * @return Response
     */
    public function addFriend(Request $request, User $friend, FriendshipManager $friendshipManager): Response
    {
        // If not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // User you want to add cannot be found
        if (null === $friend) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }

        // Get current User
        $user = $this->getUser();

        // Check if request has already been made
        /*$friendship = $friendshipRepository->findOneBy([
            'sender' => $user,
            'receiver' => $friend,
        ]);*/
        $friendship = $friendshipManager->get($user, $friend);

        // If friendship is new
        if (null === $friendship)
        {   
            $friendshipManager->create($user, $friend);         
            /*$req = $user->addFriend($friend);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($req);
            $entityManager->flush();*/

            //$this->addFlash('success', 'Votre demande a bien été envoyé !');
            $this->addFlash('success', $friend->getNickname().' a été ajouté à vos amis !');
        } 
        elseif ($friendship->getStatus() == 1)
        {
            $this->addFlash('success', 'Vous êtes déjà amis !');
        } 
        else
        {
            $this->addFlash('warning', 'Votre demande a déjà été envoyé !');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * List all friends
     *
    */
    public function friendsList(FriendshipRepository $friendshipRepository): Response
    {
        // If not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Get current User
        $user = $this->getUser();

        $friends = $friendshipRepository->findAllFriends($user->getId());

        return $this->render('profile/_friend_list.html.twig', [
            'friends' => $friends
        ]);
    }


}
