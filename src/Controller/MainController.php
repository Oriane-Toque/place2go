<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {


	/**
	 * Permet d'accéder à la page de contact
	 *
	 * @Route("/contact", name="app_contact_contact", methods={"GET", "POST"})
	 * @return Response
	 */
	public function contact(Request $request): Response {

		$user = $this->getUser();
		$this->denyAccessUnlessGranted('USER_ACCESS', $user, 'Requirements not met');

		$form = $this->createForm(ContactType::class);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			// todo
		}

		return $this->render("contact/contact.html.twig", [
			'form' => $form->createView(),
		]);
	}
}