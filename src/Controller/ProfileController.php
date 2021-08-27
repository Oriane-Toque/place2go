<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfileController extends AbstractController
{
	/**
	 * Display user profile (public)
	 * 
	 * @Route("/profile/{id<\d+>}", name="app_profile_show", methods={"GET"})
	 * 
	 * @param User $user
	 * 
	 * @return Response
	 */
	public function show(User $user = null): Response
	{
		return $this->render('profile/show.html.twig', [
			"user" => $user,
		]);
	}

	/**
	 * Display user profile (private / dashboard)
	 * 
	 * @Route("/profile", name="app_profile_profile", methods={"GET"})
	 * @isGranted("ROLE_USER")
	 * 
	 * @param EventRepository $eventRepository
	 * 
	 * @return Response
	 */
	public function profile(EventRepository $eventRepository): Response
	{
		// (MVP) je dois récupérer le nom, prénom, email, description du user
		// TODO (V2) je dois récupérer les notifications au sujet de mes amis

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

		// rGet current User
		$user = $this->getUser();

		// Last 3 created events by the user ordered by date
		$authorLastThreeExits = $eventRepository->findLastAuthorEvents($user->getId(), 3);

		// Last 3 joined events by the user ordered by date
		$attendantLastThreeExits = $eventRepository->findLastAttendantEvents($user->getId(), 3);

		return $this->render('profile/profile.html.twig', [
			"user" => $user,
			"userLastExits" => $authorLastThreeExits,
			"attendantLastExits" => $attendantLastThreeExits,
		]);
	}

	/**
	 * Edit user profile
	 * 
	 * @Route("/profile/edit", name="app_profile_edit", methods={"GET","POST"})
	 * 
	 * @param Request $request
	 * @param UserPasswordHasherInterface $passwordHasher
	 * 
	 * @return Response
	 */
	public function edit(Request $request, UserPasswordHasherInterface $passwordHasher): Response
	{
		// je récupère l'utilisateur courant
		$user = $this->getUser();

		$form = $this->createForm(RegistrationFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			// Password hash if user is trying to update it
			if ($form->get('password')->getData() != '') {
				$hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
				$user->setPassword($hashedPassword);
			}

			$this->getDoctrine()->getManager()->flush();

			// redirection vers le dashboard
			return $this->redirectToRoute('app_profile_profile', [], Response::HTTP_SEE_OTHER);
		}

		return $this->renderForm('profile/edit.html.twig', [
			'user' => $user,
			'form' => $form,
		]);
	}

	/**
	 * Send a friend request
	 * 
	 * @Route("/profile/friends/{id<\d+>}/add", name="app_profile_friend_add", methods={"GET"})
	 * @isGranted("ROLE_USER")
	 * 
	 * @param Request $request
	 * @param User $receiver
	 * @param FriendshipRepository $friendshipRepository
	 * 
	 * @return Response
	 */
	public function addFriend(Request $request, User $friend, FriendshipRepository $friendshipRepository): Response
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

		$user->addFriend($friend);

		// Check if request has already been made
		$friendship = $friendshipRepository->findOneBy([
			'sender' => $user,
			'receiver' => $friend,
		]);

		// If friendship is new
        if (null === $friendship)
		{
            // Create a friend request
            /*$friendRequest = new Friendship();
            $friendRequest
                ->setSender($user)
                ->setReceiver($friend)
				//->setStatus(1)
                //->setCreatedAt(new \DateTimeImmutable());

            // Push in database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($friendRequest);
            $entityManager->flush();*/

			$user->addFriend($friend);

			//dd($request);

			// Push in database
            /*$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($request);
            $entityManager->flush();*/


            $this->addFlash('success', 'Votre demande a bien été envoyé !');
        }
		elseif ($friendship->getStatus() == 1){
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
	 * List all friend requests
	 * 
	 * @Route("/profile/friend/request", name="app_profile_friend_request", methods={"GET"})
	 * @isGranted("ROLE_USER")
	 * 
	 * @param FriendshipRepository $friendshipRepository
	 * 
	 * @return Response
	 */
	public function listFriendRequest(FriendshipRepository $friendshipRepository): Response
	{
		// If not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

		// Get current User
		$user = $this->getUser();

		// List all friend requests received
		$friendRequestReceived = $friendshipRepository->findBy(['receiver' => $user]);

		// List all friend requests send
		$friendRequestSend = $friendshipRepository->findBy(['sender' => $user]);

		return $this->renderForm('profile/friend_request.html.twig', [
			'friendRequestReceived' => $friendRequestReceived,
			'friendRequestSend' => $friendRequestSend,
		]);
	}

	/**
	 * List all friend
	 * 
	 * @Route("/profile/friend", name="app_profile_friend", methods={"GET"})
	 * @isGranted("ROLE_USER")
	 * 
	 * @param FriendshipRepository $friendshipRepository
	 * 
	 * @return Response
	 */
	public function friendList(FriendshipRepository $friendshipRepository): Response
	{
		// If not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

		// Get current User
		$user = $this->getUser();

		// List all friend requests received
		$friends = $friendshipRepository->findBy(['receiver' => $user]);


		return $this->renderForm('profile/friend.html.twig', [
			'friends' => $friends,
		]);
	}

	/**
	 * List all friends in user profile
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

		$friends = $friendshipRepository->findFriends($user->getId());
		//$friends = $user->getFriends();

		dump($friends);

		return $this->render('profile/_friend_list.html.twig', [
            'friends' => $friends
		]);
	}



	public function getCountFriendRequest(FriendshipRepository $friendshipRepository)
	{
		// If not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

		// Get current User
		$user = $this->getUser();

		return $friendshipRepository->getTotalFriendRequest($user->getId());
	}

}
