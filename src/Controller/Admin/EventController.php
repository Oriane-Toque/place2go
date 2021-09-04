<?php

namespace App\Controller\Admin;

use App\Data\SearchData;
use App\Entity\Event;
use App\Form\EventType;
use App\Form\SearchFormType;
use App\Repository\AttendantRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */

class EventController extends AbstractController
{
    /**
     * @Route("/admin/events", name="admin_event_list", methods={"GET"})
     */
    public function list(EventRepository $eventRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Find all events
        //$events = $eventRepository->findAll();
        $data = array();
        $query = $eventRepository->findAllQuery($data);
        
        $events = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        //dd($request->query->get('filterValue'));

        $searchData = new SearchData();
        $form = $this->createForm(SearchFormType::class, $searchData, [
            'action' => $this->generateUrl('admin_event_list'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data['title'] = $form->get('q')->getData();
            $query = $eventRepository->findAllQuery($data);
    
            $events = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            );
    
        }

        return $this->render('admin/event/list.html.twig', [
            'form' => $form->createView(),
            'events' => $events,
        ]);
    }

    /**
     * @Route("/admin/events/{id<\d+>}/show", name="admin_event_show", methods={"GET"})
     */
    public function show(Event $event, AttendantRepository $attendantRepository): Response
    {
        // Attendants for this event
        $eventUsers = $attendantRepository->findAttendantsByEvent($event);

        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
            'eventUsers' => $eventUsers,
        ]);
    }

    /**
     * @Route("/admin/events/create", name="admin_event_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        // New object
        $event = new Event();

        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setAuthor($this->getUser());
            // Add coordinates to event
            $event->setLat('');
            $event->setLon('');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Sortie créée avec succès !');

            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render('admin/event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/events/{id<\d+>}/edit", name="admin_event_edit", methods={"GET", "POST"})
     */
    public function edit(Event $event, Request $request): Response
    {
        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // No persist on edit
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Sortie modifiée avec succès !');

            return $this->redirectToRoute('admin_event_list');
        }

        return $this->render('admin/event/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/events/{id<\d+>}/delete", name="admin_event_delete", methods={"GET"})
     */
    public function delete(Event $event): Response
    {
        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        // Flash message
        $this->addFlash('success', 'Sortie supprimée avec succès');

        return $this->redirectToRoute('admin_event_list');
    }

    /**
     * @Route("/admin/events/{id<\d+>}/desactive", name="admin_event_desactive", methods={"GET"})
     */
    public function desactive(Event $event): Response
    {
        // Set IsActive to 0
        $event->setIsActive(0);

        $entityManager = $this->getDoctrine()->getManager();
        // No persist on edit
        $entityManager->flush();

        // Flash message
        //$this->addFlash('success', 'Sortie '. $event->getId() . ' a été désactivé !');

        return $this->redirectToRoute('admin_event_show', ['id' => $event->getId()]);
    }

    /**
     * @Route("/admin/events/{id<\d+>}/active", name="admin_event_active", methods={"GET"})
     */
    public function active(Event $event): Response
    {
        // Set IsActive to 1
        $event->setIsActive(1);

        $entityManager = $this->getDoctrine()->getManager();
        // No persist on edit
        $entityManager->flush();

        // Flash message
        //$this->addFlash('success', 'Sortie '. $event->getId() . ' a été activé !');

        return $this->redirectToRoute('admin_event_show', ['id' => $event->getId()]);
    }
}
