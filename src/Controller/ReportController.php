<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Report;
use App\Entity\User;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportController extends AbstractController
{

    /**
     * Signalement d'un utilisateur
     *
     * @Route("/report/user/{id<\d+>}", name="app_report_user", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function user(Request $request, User $user = null, EntityManagerInterface $em, ReportRepository $reportRepository)
    {
        if (null === $user) {
            throw $this->createNotFoundException('Utilisateur inconnu');
        }
        
        // vérifie si l'auteur est connecté
        $this->denyAccessUnlessGranted("USER_ACCESS", $this->getUser(), "Requirements not met");

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

                return $this->redirectToRoute('app_profile_show', ['id' => $user->getId()]);
            }

            $this->addFlash('danger', 'Cet utilisateur fait déjà l\'objet d\'un signalement de votre part !');

            return $this->redirectToRoute('app_profile_show', ['id' => $user->getId()]);
        }

        return $this->renderForm('report/report.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Signalement d'une sortie
     *
     * @Route("/report/event/{id<\d+>}", name="app_report_event", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function event(Request $request, Event $event, EntityManagerInterface $em, ReportRepository $reportRepository)
    {
        // vérifie si l'auteur est connecté
        $this->denyAccessUnlessGranted("USER_ACCESS", $this->getUser(), "Requirements not met");
        $this->denyAccessUnlessGranted('EVENT_SHOW', $event, "Requirements not met");

        // création d'un nouveau signalement
        $report = new Report;

        // récupération de l'utilisateur qui signale
        $author = $this->getUser();
        // utilisateur de la sortie signalé
        $user = $event->getAuthor();

        // création du formulaire
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setAuthor($author);
            // utilisateur signalé
            $report->setUser($user);
            // sortie de l'utilisateur signalé
            $report->setEvent($event);

            if (!$reportRepository->findOneBy(["user" => $user, "author" => $author])) {
                $em->persist($report);
                $em->flush();

                $this->addFlash('success', 'Votre rapport a bien été envoyé aux modérateurs ! Et sera traité dans les plus brefs délais');

                return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
            }

            $this->addFlash('danger', 'Cet utilisateur fait déjà l\'objet d\'un signalement de votre part !');

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->renderForm('report/report.html.twig', [
            'form' => $form,
        ]);
    }
}
