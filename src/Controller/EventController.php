<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Data\SearchData;
use App\Entity\Attendant;
use App\Form\SearchFormType;
use App\Services\isAttendant;
use App\Repository\EventRepository;
use App\Repository\AttendantRepository;
use App\Services\CallApiService;
use App\Services\GeoJson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EventController extends AbstractController
{

	private $callApiService;
	private $geoJson;

	public function __construct(CallApiService $callApiService, geoJson $geoJson)
	{
		$this->callApiService = $callApiService;
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

		// Get results based on user search
		$events = $eventRepository->findSearch($data);
		// Get location of current query to show it on the map
		$location = $this->callApiService->getApi($data->q);
		// Make a geoJson with all results and send it to view to render pins on map
		$geoJson = $this->geoJson->createGeoJson($events);

		return $this->render('event/list.html.twig', [
			'events' => $events,
			'form' => $form->createView(),
			'geojson' => $geoJson,
			'location' => $location,
		]);
	}

	/**
	 * @Route("/events/create", name="app_event_create", methods={"GET", "POST"})
	 * 
	 * @param Request $request
	 * 
	 * @return Response
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
			// Add coordinates to Event

			$coordinates = $this->callApiService->getApi($form['address']->getData());

			// set coordinates fetched from geoAPI
			$event->setLat($coordinates[0]);
			$event->setLon($coordinates[1]);

			// push into the database
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($event);
			dump($event);
			$entityManager->flush();

			// Flash message
			$this->addFlash('success', 'Sortie créée avec succès !');

			return $this->redirectToRoute('app_event_show', [
				'id' => $event->getId(),
			]);
		}

		return $this->render('event/create.html.twig', [
			'form' => $form->createView(),
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

		return $this->render('event/edit.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/events/{id<\d+>}/show", name="app_event_show", methods={"GET"})
	 * 
	 * @param Event $event
	 * 
	 * @return Response
	 */
	public function show(Event $event): Response
	{
		return $this->render('event/show.html.twig', [
			'event' => $event,
		]);
	}


	/**
	 * TODO optimiser la méthode pour éviter de passer par l'id
	 * 
	 * @Route("/events/{id<\d+>}/delete", name="app_event_delete", methods={"GET"})
	 * 
	 * @param Event $event
	 * @param Request $request
	 * 
	 * @return Response
	 */
	public function delete(Event $event, Request $request): Response
	{
		// Remove from BDD
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($event);
		$entityManager->flush();

		// Flash message
		$this->addFlash('success', 'Sortie supprimée avec succès');

		//! redirection dans la page courante
		// solution pour éviter une redirection vers la page liste
		// quand on supprime depuis le profil privé
		return $this->redirect($request->headers->get('referer'));
	}
}
