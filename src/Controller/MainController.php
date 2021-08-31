<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {


	/**
	 * Permet d'accéder à la page de contact
	 *
	 * @Route("/contact", name="app_contact_contact", methods={"GET", "POST"})
	 * @return Response
	 */
	public function contact(Request $request, MailerInterface $mailer): Response {

		$user = $this->getUser();
		$this->denyAccessUnlessGranted('USER_ACCESS', $user, 'Requirements not met');

		$form = $this->createForm(ContactType::class);
		$contact = $form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			// on crée le mail
			$email = (new TemplatedEmail())
				->from(new Address($user->getEmail()))
				->to(new Address('checkmyapplications@gmail.com', 'Place 2 Go Emailer'))
				->subject('Place2Go - '.$contact->get('subject')->getData())
				->cc(new Address($user->getEmail()))
				->htmlTemplate('contact/contact_email.html.twig')
				->context([
					'user' => $user,
					'subject' => $contact->get('subject')->getData(),
					'message' => $contact->get('message')->getData(),
				]);
			// on envoie le mail
			$mailer->send($email);

			$this->addFlash('success', 'Votre email a bien été envoyé et sera traité dans les plus brefs délais !');
			
			return $this->redirectToRoute('app_home');
		}

		return $this->render("contact/contact.html.twig", [
			'form' => $form->createView(),
		]);
	}

	/**
	 * Display legal notice page
	 *
	 * @Route("/legal-notice", name="app_legal_notice", methods={"GET"})
	 * @return Response
	 */
	public function legalNotice(): Response {

		return $this->render("contact/legal_notice.html.twig");
	}

	/**
	 * Display privacy policy page
	 *
	 * @Route("/privacy_policy", name="app_privacy_policy", methods={"GET"})
	 * @return Response
	 */
	public function privacyPolicy(): Response {

		return $this->render("contact/privacy_policy.html.twig");
	}
}