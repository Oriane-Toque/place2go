<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
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
}
