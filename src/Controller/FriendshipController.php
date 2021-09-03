<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchFriendType;
use App\Services\FriendshipManager;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class FriendshipController extends AbstractController
{

		/**
		 * Send a friend request
		 *
		 * @Route("/profile/friends/{id<\d+>}/add", name="app_friend_add", methods={"GET"})
		 *
		 * @param User $friend
		 * @param FriendshipManager $friendshipManager
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function addFriend(User $friend, FriendshipManager $friendshipManager, Request $request): Response
		{
			$this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), 'Vous ne pouvez pas accéder à cette page');
			$this->denyAccessUnlessGranted('USER_ACCESS', $friend, 'Vous ne pouvez pas accéder à cette page');

			// Get current User
			$user = $this->getUser();

			// Check if friendship request has not already been made
			// In this case we create it
			$friendship = $friendshipManager->get($user, $friend);

			if (null === $friendship) {
				$friendshipManager->create($user, $friend);

				//$this->addFlash('success', 'Votre demande a bien été envoyé !');
				$this->addFlash('success', $friend->getNickname() . ' a été ajouté à vos amis !');
			} elseif ($friendship->getStatus() == 1) {
				$this->addFlash('success', 'Vous êtes déjà ami avec ' . $friend->getNickname() . ' !');
			} else {
				$this->addFlash('warning', 'Votre demande a déjà été envoyé !');
			}

			$referer = $request->headers->get('referer');
			return $this->redirect($referer);
		}

		/**
		 * List all friends in the user profile
		 *
		 * @param FriendshipManager $friendshipManager
		 *
		 * @return Response
		 */
		public function privateFriendsList(FriendshipRepository $friendshipRepository): Response
		{
			// Get current User
			$user = $this->getUser();

			$friends = $friendshipRepository->findAllFriends($user->getId());

			return $this->render('profile/_private_friend_list.html.twig', [
				'friends' => $friends
			]);
		}

		/**
		 * List all friends in the public user profile
		 *
		 * @param Int $id
		 * @param FriendshipManager $friendshipManager
		 *
		 * @return Response
		 */
		public function friendsList(Int $id, FriendshipRepository $friendshipRepository): Response
		{
			$friends = $friendshipRepository->findAllFriends($id);

			return $this->render('profile/_friend_list.html.twig', [
				'friends' => $friends
			]);
		}

		/**
		 * Delete a friend from friendlist
		 *
		 * @Route("/profile/friends/{id<\d+>}/delete", name="app_friend_delete", methods={"GET"})
		 *
		 * @param User $friend
		 * @param FriendshipManager $friendshipManager
		 * @param Request $request
		 *
		 * @return Response
		 *
		 */
		public function deleteAction(User $friend, FriendshipManager $friendshipManager, Request $request): Response
		{
			// Get current User
			$user = $this->getUser();

			if ($friendshipManager->delete($user, $friend)) {
				$this->addFlash('success', 'Supprimer ' . $friend->getNickname() . ' ? <a href=""');
			}

			$referer = $request->headers->get('referer');
			return $this->redirect($referer);
		}

		/**
		 * Delete a friend from friendlist
		 *
		 * @Route("/profile/friends/{id<\d+>}/delete", name="app_friend_delete", methods={"GET", "POST"})
		 *
		 * @param User $friend
		 * @param FriendshipManager $friendshipManager
		 * @param Request $request
		 *
		 * @return Response
		 *
		 */
		public function deleteFriend(User $friend, FriendshipManager $friendshipManager, Request $request): Response
		{
			// Get current User
			$user = $this->getUser();

			if ($friendshipManager->delete($user, $friend)) {
				$this->addFlash('success', $friend->getNickname() . ' a été retiré de votre liste ;(');
			}

			$referer = $request->headers->get('referer');
			return $this->redirect($referer);
		}
}
