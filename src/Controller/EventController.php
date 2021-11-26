<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\EventType;
use App\Data\SearchData;
use App\Form\CommentType;
use App\Services\GeoJson;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use App\Services\FriendshipManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    private $geoJson;

    public function __construct(geoJson $geoJson)
    {
        $this->geoJson = $geoJson;
    }

    /**
     * @Route("/events", name="app_event_list", methods={"GET"})
     *
     * @param Request $request
     * @param EventRepository $eventRepository
     *
     * @return Response
     */
    public function list(Request $request, EventRepository $eventRepository): Response
    {
        // Init Data to handle form search
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);

        // Handle the form request and use $data in custom query to show searched events
        $form->handleRequest($request);

        $events = $eventRepository->findSearch($data);
        // Make a geoJson from results to render pin on map
        $geoJson = $this->geoJson->createGeoJson($events);

        // Get coords of the requested city
        if (!empty($events)) {
            $location = [$geoJson['features'][0]['geometry']['coordinates'][0], $geoJson['features'][0]['geometry']['coordinates'][1]];
        } else {
            $location = [1, 47];
        }

        return $this->renderForm('event/list.html.twig', [
            'events' => $events,
            'geojson' => $geoJson,
            'location' => $location,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/events/create", name="app_event_create", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request, FriendshipManager $friendshipManager): Response
    {
        $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), "Vous n'avez pas les autorisations nécessaires");
        $event = new Event();

        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setAuthor($this->getUser());
            // push into the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            $notif = $friendshipManager->eventAllFriendsNotifier($this->getUser(), $event);

            if ($notif) {
                $this->addFlash('success', 'Votre sortie a bien été créée. Vos amis seront notifiés par email !');
            } else {
                $this->addFlash('success', 'Votre sortie à bien été créée ! ');
            }
        
            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->renderForm('event/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/events/{id<\d+>}/edit", name="app_event_edit", methods={"GET", "POST"})
     *
     * @param Event $event
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Event $event, Request $request): Response
    {
        $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), "Vous n'avez pas les autorisations nécessaires");
        $this->denyAccessUnlessGranted('EVENT_EDIT', $event, "Vous n'avez pas les autorisations nécessaires");

        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée !');

            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->renderForm('event/edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/events/{id<\d+>}/show", name="app_event_show", methods={"GET", "POST"})
     *
     * @param Event $event
     * @param Request $request
     *
     * @return Response
     */
    public function show(Event $event, Request $request): Response
    {
        $this->denyAccessUnlessGranted("EVENT_SHOW", $event, "Impossible d'effectuer cette action");

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // Create new form associated to entity
        if ($form->isSubmitted() && $form->isValid()) {
            // Check access
            $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), 'Accès refusé');
            // set the author to the  associated comment
            $comment->setAuthor($this->getUser());
            // set the event
            $comment->setEvent($event);
            $comment->setCreatedAt(new DateTimeImmutable('now', new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté');

            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->renderForm('event/show.html.twig', [
            'event' => $event,
            'comments' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/events/{id<\d+>}/delete", name="app_event_delete", methods={"GET"})
     *
     * @param Event $event
     * @param Request $request
     *
     * @return Response
     */
    public function delete(Event $event, Request $request): Response
    {
        $this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), "Vous n'avez pas les autorisations nécessaires");
        $this->denyAccessUnlessGranted('EVENT_DELETE', $event, "Vous n'avez pas les autorisations nécessaires");

        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success', 'Sortie supprimée avec succès');

        //? Handle redirect to previous visited page
        return $this->redirect($request->headers->get('referer'));
    }
}
