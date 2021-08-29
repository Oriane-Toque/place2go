<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\User;
use App\Form\ReportType;
use App\Repository\ReportRepository;
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
	public function user(Request $request, User $user = null, EntityManagerInterface $em, ReportRepository $reportRepository)
	{
		if(null === $user) {
			throw $this->createNotFoundException("404");
		}

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
			
        if (!$reportRepository->findOneBy(["user" => $user, "author" => $author])) {

            $em->persist($report);
            $em->flush();

            $this->addFlash('success', 'Votre rapport a bien été envoyé aux modérateurs ! Et sera traité dans les plus brefs délais');

            return $this->redirect($request->headers->get('referer'));
        }

				$this->addFlash('danger', 'Vous avez déjà signalé cet utilisateur !');

				return $this->redirectToRoute('app_profile_show', ['id' => $user->getId()]);
		}

		return $this->render('report/report.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
