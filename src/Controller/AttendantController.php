<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Attendant;
use App\Repository\AttendantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @isGranted("ROLE_USER")
 */
class AttendantController extends AbstractController
{
    private $attendantRepository;
    private $em;

    public function __construct(AttendantRepository $attendantRepository, EntityManagerInterface $em)
    {
        $this->attendantRepository = $attendantRepository;
        $this->em = $em;
    }

    /**
     * To join an event
     * @Route("/event/{id<\d+>}/join", name="app_event_join", methods={"GET"})
     *
     * @param Event $event
     * @param Request $request
     *
     * @return Response
     */
    public function join(Event $event, Request $request): Response
    {
        //check autorizations
        $this->denyAccessUnlessGranted("USER_ACCESS", $this->getUser(), "Requirements not met");
        $this->denyAccessUnlessGranted('EVENT_JOIN', $event, "Vous ne pouvez pas rejoindre cette sortie");

        // If the current number of attendants is equal or superior to the maximum number of attendants, redirect
        if ($event->getAttendants()->count() === $event->getMaxAttendants()) {
            $this->addFlash('warning', "Il n'y a plus de places disponibles");

            return $this->redirect($request->headers->get('referer'));
        }

        $attendant = new Attendant();
        $attendant->setUser($this->getUser());
        $attendant->setEvent($event);

        $this->em->persist($attendant);
        $this->em->flush();

        $this->addFlash('success', 'Votre inscription à ' . $event->getTitle() . ' est bien enregistrée !');

        // Redirection sur la page de l'évènement correspondant
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * To leave an event
     * @Route("/event/{id<\d+>}/leave", name="app_event_leave", methods={"GET"})
     *
     * @param Event $event
     * @param Request $request
     *
     * @return Response
     */
    public function leave(Event $event, Request $request): Response
    {
        $this->denyAccessUnlessGranted("USER_ACCESS", $this->getUser(), "Requirements not meet");
        $this->denyAccessUnlessGranted('EVENT_LEAVE', $event, "Vous ne pouvez pas quitter une sortie dont vous êtes l'auteur !");

        $attendant = $this->attendantRepository->findOneBy(['event' => $event, 'user' => $this->getUser()]);

        // Delete the user from attendant list
        $this->em->remove($attendant);
        $this->em->flush();

        // Flash message
        $this->addFlash('success', 'Votre participation a été supprimée :(');

        // redirection dans la page courante
        return $this->redirect($request->headers->get('referer'));
    }
}
