<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Services\FriendshipManager;
use App\Repository\FriendshipRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function show(User $user, FriendshipManager $friendshipManager): Response
    {
        $this->denyAccessUnlessGranted('USER_ACCESS', $user, "Impossible d'accéder à ce profil");

        // Check if friendship exists
        $friendship = $friendshipManager->get($this->getUser(), $user);

        return $this->render('profile/show.html.twig', [
            "user" => $user,
            "friendship" => $friendship
        ]);
    }

    /**
     * Display user profile (private / dashboard)
     *
     * @Route("/profile", name="app_profile_profile", methods={"GET", "POST"})
     *
     * @param EventRepository $eventRepository
     *
     * @return Response
     */
    public function profile(EventRepository $eventRepository, FriendshipRepository $friendshipRepository): Response
    {
        $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), "Vous n'avez pas les autorisations nécessaires");

        // Get current User
        $user = $this->getUser();

        // Last 3 created events by the user ordered by date
        $authorLastThreeExits = $eventRepository->findLastAuthorEvents($user->getId(), 3);

        // Last 3 joined events by the user ordered by date
        $attendantLastThreeExits = $eventRepository->findLastAttendantEvents($user->getId(), 3);

        // My friends last events
        $friends = $friendshipRepository->findAllFriends($user);

        $friendsAuthor = [];
        $friendsAttendant = [];

        foreach ($friends as $friend) {
            $friendsAuthor[] = $eventRepository->findNextAuthorEvents($friend->getId());
            $friendsAttendant[] = $eventRepository->findNextAttendantEvents($friend->getId());
        }

        return $this->render('profile/profile.html.twig', [
            "user" => $user,
            "userLastExits" => $authorLastThreeExits,
            "attendantLastExits" => $attendantLastThreeExits,
            "friends" => $friends,
            "friendsAuthor" => $friendsAuthor,
            "friendsAttendant" => $friendsAttendant
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
        $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), "Vous n'avez pas les autorisations nécessaires");

        // Get current User
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Password hash if user is trying to update it
            // If old password (and corresponding to user password) + new password sent
            if (!empty($form->get('oldpassword')->getData()) && !empty($form->get('password')->getData()) && $passwordHasher->isPasswordValid($user, $form->get('oldpassword')->getData())) {
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);

                $this->addFlash('success', 'Mot de passe modifié !');
            }

            $this->getDoctrine()->getManager()->flush();

            // Redirect to dashboard
            return $this->redirectToRoute('app_profile_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Display all events created by the user
     *
     * @Route("/profile/events", name="app_profile_events", methods={"GET"})
     *
     * @param EventRepository $eventRepository
     *
     * @return Response
     */
    public function showEvents(EventRepository $eventRepository): Response
    {
        $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), "Vous n'avez pas les autorisations nécessaires");

        // Get current User
        $user = $this->getUser();

        // All created events by the user ordered by date
        $authorEvents = $eventRepository->findLastAuthorEvents($user->getId());

        return $this->render('profile/historical.html.twig', [
            "user" => $user,
            "userEvents" => $authorEvents,
        ]);
    }
}
