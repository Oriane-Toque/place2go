<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {


	/**
	 * Permet d'accéder à la page de contact
	 *
	 * @Route("/contact", name="app_contact_contact", methods={"GET", "POST"})
	 * @return Response
	 */
	public function contact(): Response {

		return $this->render("contact/contact.html.twig");
	}
}