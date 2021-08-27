<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\User;
use App\Form\ReportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{

	/**
	 * Signalement d'un utilisateur
	 *
	 * @Route("/report/user/{id<\d+>}", name="app_report_user", methods={"GET", "POST"})
	 */
	public function user(Request $request, User $user, EntityManagerInterface $em)
	{

		$this->denyAccessUnlessGranted("PRIVATE_ACCESS", $this->getUser(), "Requirements not met");

		// création d'un nouveau signalement
		$report = new Report;

		// récupération de l'utilisateur qui signale
		$author = $this->getUser();

		// création du formulaire
		$form = $this->createForm(ReportType::class, $report);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$report->setAuthor($author);
			// utilisateur signalé
			$report->setUser($user);
			
			$em->persist($report);
			$em->flush();

			$this->addFlash('success', 'Votre rapport a bien été envoyé aux modérateurs ! Et sera traité dans les plus brefs délais');

			return $this->redirect($request->headers->get('referer'));
		}

		return $this->render('report/report.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
