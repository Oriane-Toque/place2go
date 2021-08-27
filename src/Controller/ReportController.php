<?php

namespace App\Controller;

use App\Entity\Report;
use App\Form\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController {

	/**
	 * Signalement d'un utilisateur
	 *
	 * @Route("/report", name="app_report", methods={"GET", "POST"})
	 */
	public function report(Request $request) {

	}
}