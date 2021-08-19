<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
		/**
		 * Display user profile (public)
		 * 
		 * @Route("/profile/{id<\d+>}", name="app_profile_show", methods={"GET"})
		 */
		public function show(User $user = null): Response
		{

			if (null === $user) {

				throw $this->createNotFoundException('404 - Profil utilisateur introuvable');
			}

			return $this->render('profile/show.html.twig', [
				"user" => $user,
			]);
		}

		/**
		 * Display user profile (private / dashboard)
		 *
		 * @Route("/profile", name="app_profile_profile", methods={"GET"})
		 */
		public function profile(EventRepository $eventRepository): Response
		{

			// (MVP) je dois récupérer le nom, prénom, email, description du user
			// TODO (V2) je dois récupérer les notifications au sujet de mes amis

			// je vérifie si un utilisateur est connecté
			if($this->getUser()) {
				// récupération de l'utilisateur connecté
				$user = $this->getUser();

				// création de deux requêtes custom dans EventRepository 
				// pour récupèrer les trois dernières sorties proposées et auxquels il participe

				// 3 dernières sorties dont il est l'auteur de l'évènement le plus récent au plus ancien
				$authorLastThreeExits = $eventRepository->findLastThreeAuthorEvents($user->getId());

				// 3 dernières sorties dont il est le participant de l'évènement le plus récent au plus ancien
				$attendantLastThreeExits = $eventRepository->findLastThreeAttendantEvents($user->getId());

				dump($attendantLastThreeExits);
				return $this->render('profile/profile.html.twig', [
					"user" => $user,
					"userLastExits" => $authorLastThreeExits,
					"attendantLastExits" => $attendantLastThreeExits,
				]);
			}
			
			return $this->redirectToRoute("app_login");
		}

		/**
		 * Edit user profile
		 * 
     * @Route("profile/edit", name="app_profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
				// je récupère l'utilisateur courant
				$user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // hashage du mdp que si on a renseigné le champs mot de passe
            if ($form->get('password')->getData() != '') {

                $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());

                $user->setPassword($hashedPassword);
            }

						// on insère les nouvelles données
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

