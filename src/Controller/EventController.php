<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events", methods={"GET"})
     */
    public function list(EventRepository $eventRepository): Response
    {    
        $events = $eventRepository->findAll();

        return $this->render('events/list.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/events/{id}/show", name="event_show", methods={"GET"})
     */
    public function show(Event $event = null): Response
    {
        // 404 ?
        if ($event === null) {
            throw $this->createNotFoundException( 'Sortie introuvable');
        }

        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }

}
