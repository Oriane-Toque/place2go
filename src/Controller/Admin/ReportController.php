<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @isGranted("ROLE_ADMIN")
 */
class ReportController extends AbstractController
{

    /**
     * Liste les signalements en cours de traitement
     *
     * @Route("/admin/reports", name="admin_report_list", methods={"GET"})
     *
     * @param ReportRepository $rr
     *
     * @return Response
     */
    public function list(ReportRepository $rr): Response
    {
        $reports = $rr->findBy(['status' => false], [
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
     *
     * @param ReportRepository $rr
     *
     * @return Response
     */
    public function archive(ReportRepository $rr): Response
    {
        $reports = $rr->findBy(['status' => true], [
            'createdAt' => 'DESC',
        ]);

        return $this->render("admin/report/list.html.twig", [
            'reports' => $reports,
        ]);
    }

    /**
     * Page détail d'un signalement
     *
     * @Route("/admin/reports/{id<\d+>}", name="admin_report_show", methods={"GET"})
     *
     * @param Report $report
     *
     * @return Response
     */
    public function show(Report $report): Response
    {
        return $this->render('admin/report/show.html.twig', [
            'report' => $report,
        ]);
    }

    /**
     * Change le statut d'un signalement (en cours/traité)
     *
     * @Route("/admin/reports/{id<\d+>}/status", name="admin_report_status", methods={"GET"})
     *
     * @param Report $report
     * @param EntityManagerInterface $em
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function process(Report $report, EntityManagerInterface $em, Request $request): RedirectResponse
    {
        if ($report->getStatus() === false) {
            $report->setStatus(true);

            $this->addFlash('success', 'Signalement traité !');
        } else {
            $report->setStatus(false);

            $this->addFlash('success', 'Signalement en cours de traitement !');
        }

        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Supprimer un signalement
     *
     * @Route("/admin/report/{id<\d+>}/delete", name="admin_report_delete", methods={"GET"})
     *
     * @param Report $report
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse
     */
    public function delete(Report $report, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($report);
        $em->flush();

        $this->addFlash('success', 'Signalement supprimé');

        return $this->redirectToRoute('admin_report_list');
    }
}
