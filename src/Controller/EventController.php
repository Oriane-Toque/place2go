<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Data\SearchData;
use App\Services\GeoJson;
use App\Form\SearchFormType;
use App\Services\CallApiService;
use App\Repository\EventRepository;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
		 * TODO GÉRER SI IL N'Y A PAS DE SORTIES POUR UNE CATÉGORIE DONNÉE
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
			$geoJson = $this->geoJson->createGeoJson($events);

			

			if (!empty($data->q)){
				$location = $this->callApiService->getApi($data->q);
			} elseif (!empty($events)) {
				$location = [$geoJson['features'][0]['geometry']['coordinates'][0], $geoJson['features'][0]['geometry']['coordinates'][1]];
			} else {
				$location = [1, 47];
			}

			return $this->render('event/list.html.twig', [
				'events' => $events,
				'form' => $form->createView(),
				'geojson' => $geoJson,
				'location' => $location,
			]);
		}

		/**
		 * Return all events for one category with city's user in params
		 *
		 * @Route("events/category/{id<\d+>}/search", name="app_events_category_search", methods={"GET"})
		 * 
		 * @return Response renvoie sur la page liste filtré selon la catégorie et la ville paramétré sur le compte utilisateur
		 * si pas connecté renvoie toutes les sorties dans n'importe quelle ville selon la catégorie
		 */
		public function searchByCategory(Request $request, Category $category, EventRepository $er): Response
		{

			// Init Data to handle form search
			$data = new SearchData();
			$form = $this->createForm(SearchFormType::class, $data);

			// Handle the form request and use $data in custom query to show searched events
			$form->handleRequest($request);
			$events = $er->findSearch($data);

			if (null === $category) {
				throw $this->createNotFoundException('404 - Catégorie introuvable');
			}

			if ($this->getUser()) {

				$city = $this->getUser()->getCity();

				$events = $er->findEventsByCategory($category->getId(), $city);

				return $this->render('event/list.html.twig', [
					'events' => $events,
					'form' => $form->createView(),
				]);
			}

			$events = $er->findEventsByCategory($category->getId());

			return $this->render('event/list.html.twig', [
				'events' => $events,
				'form' => $form->createView(),
			]);
		}

		/**
		 * @Route("/events/create", name="app_event_create", methods={"GET", "POST"})
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
		 */
		public function edit(Event $event, Request $request): Response
		{
			// Create new form associated to entity
			$form = $this->createForm(EventType::class, $event);

			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {

				$coordinates = $this->callApiService->getApi($form['address']->getData());

				// set coordinates fetched from geoAPI
				$event->setLat($coordinates[0]);
				$event->setLon($coordinates[1]);

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

			$this->addFlash('success', 'Sortie supprimée avec succès');

			//? Handle redirect to previous visited page
			return $this->redirect($request->headers->get('referer'));
		}
}
