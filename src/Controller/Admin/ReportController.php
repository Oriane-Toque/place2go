<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController {

	/**
	 * Liste les signalements en cours de traitement
	 *
	 * @Route("/admin/reports", name="admin_report_list", methods={"GET"})
	 * @param ReportRepository $rr
	 * @return Response
	 */
	public function list(ReportRepository $rr): Response {

		$reports = $rr->findBy(['status' => false],[
			'createdAt' => 'DESC',
		]);

		return $this->render("admin/report/list.html.twig", [
			'reports' => $reports,
		]);
	}

	/**
	 * Liste les signalements traités/archivés
	 *
	 * @Route("/admin/reports/archive", name="admin_report_archive", methods={"GET"})
	 * @param ReportRepository $rr
	 * @return Response
	 */
	public function archive(ReportRepository $rr): Response {

		$reports = $rr->findBy(['status' => true],[
			'createdAt' => 'DESC',
		]);

		return $this->render("admin/report/list.html.twig", [
			'reports' => $reports,
		]);
	}

	/**
	 * Change le statut d'un signalement (en cours/traité)
	 *
	 * @Route("/admin/reports/{id}/status", name="admin_report_status", methods={"GET"})
	 * @param Report $report
	 * @param EntityManagerInterface $em
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function process(Report $report, EntityManagerInterface $em, Request $request): RedirectResponse {

		if($report->getStatus() === false){
			$report->setStatus(true);
		} else {
			$report->setStatus(false);
		} 

		$em->flush();

		return $this->redirect($request->headers->get('referer'));
	}

	/**
	 * Supprimer un signalement
	 *
	 * @Route("/admin/report/{id<\d+>}/delete", name="admin_report_delete", methods={"GET"})
	 */
	public function delete(Report $report, EntityManagerInterface $em, Request $request) {

		$em->remove($report);
		$em->flush();

		return $this->redirect($request->headers->get('referer'));
	}
}