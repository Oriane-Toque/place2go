<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{
    /**
     * @Route("/events", name="app_event_list", methods={"GET"})
     */
    public function list(EventRepository $eventRepository): Response
    {    
        // Find all events
        $events = $eventRepository->findAll();

        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/events/{id<\d+>}/show", name="app_event_show", methods={"GET"})
     */
    public function show(Event $event = null): Response
    {
        // 404?
        if (null === $event) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * 
     * @Route("/events/create", name="app_event_create", methods={"GET", "POST"})
     */
    public function create(Event $event = null, UserRepository $userRepository, Request $request): Response
    {

        // New object
        $event = New Event();

        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $event);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Add author to event
            $authors = $userRepository->findAll();

            $event->setAuthor($authors[0]);
            // Add coordinates to event
            $event->setLat('');
            $event->setLon('');

            // Persist event in BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Sortie créée avec succès !');

            return $this->redirectToRoute('app_event_list');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
                
    }

    /**
     * 
     * @Route("/events/{id<\d+>}/edit", name="app_event_edit", methods={"GET", "POST"})
     */
    public function edit(Event $event = null, Request $request): Response
    {

        // Event not found
        if (null === $event) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            // No persist on edit
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Sortie modifiée avec succès !');

            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('events/edit.html.twig', [
            'form' => $form->createView(),
        ]);
                 
    }

    /**
     * 
     * @Route("/events/{id<\d+>}/delete", name="app_event_delete", methods={"GET"})
     */
    public function delete(Event $event = null): Response
    {

        // 404?
        if (null === $event) {
            throw $this->createNotFoundException("Sortie introuvable.");
        }

        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        // Flash message
        $this->addFlash('success', 'Sortie supprimée avec succès');

        return $this->redirectToRoute('app_event_list');
    }

}
