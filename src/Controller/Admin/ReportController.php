<?php

namespace App\Controller\Admin;

use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController {

	/**
	 * Liste les signalements en cours de traitement
	 *
	 * @Route("/admin/reports", name="admin_report_list", methods={"GET"})
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
}