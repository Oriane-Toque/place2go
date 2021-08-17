<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
		 * Display user profile (public)
		 * 
     * @Route("/profile/{id<\d+>}", name="profile_show", methods={"GET"})
     */
    public function show(User $user = null): Response
    {

			if(null === $user) {

				throw $this->createNotFoundException('404 - Profil utilisateur introuvable');
			}

			dump($user);

      return $this->render('profile/show.html.twig', [
				"user" => $user,
			]);
    }
}
