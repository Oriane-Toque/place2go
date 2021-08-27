<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Comment;
use App\Form\EventType;
use App\Data\SearchData;
use App\Form\CommentType;
use App\Services\GeoJson;
use App\Form\SearchFormType;
use App\Repository\CommentRepository;
use App\Services\CallApiService;
use App\Repository\EventRepository;
use DateTime;
use DateTimeImmutable;
use DoctrineExtensions\Query\Mysql\Now;
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
		// Make a geoJson from results to render pin on map
		$geoJson = $this->geoJson->createGeoJson($events);

		// Get coords of the requested city
		if (!empty($data->q)) {
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
	 * @Route("/events/create", name="app_event_create", methods={"GET", "POST"})
	 * 
	 * @param Request $request
	 * 
	 * @return Response
	 */
	public function create(Request $request): Response
	{
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

			$this->addFlash('success', 'Votre sortie à bien été créée !');

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
			$entityManager->flush();

			$this->addFlash('success', 'Sortie modifiée !');

			return $this->redirectToRoute('app_event_show', [
				'id' => $event->getId(),
			]);
		}

		return $this->render('event/edit.html.twig', [
			'form' => $form->createView(),
		]);
	}


	/**
	 * @Route("/events/{id<\d+>}/show", name="app_event_show", methods={"GET", "POST"})
	 * 
	 * @param Event $event
	 * 
	 * @return Response
	 */
	public function show(Event $event, Request $request): Response
	{
		$comment = new Comment();

		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);

		if($this->getUser() == null){
			$this->addFlash('warning', 'Vous devez vous connecter pour pouvoir écrire un commentaire');
		}

		// Create new form associated to entity
		if ($form->isSubmitted() && $form->isValid()) {
			$this->denyAccessUnlessGranted('USER_ACCESS', $this->getUser(), 'Accès refusé');
			// set the author to the  associated commenb
			$comment->setAuthor($this->getUser());
			// set the event
			$comment->setEvent($event);
			// set the date
			$comment->setCreatedAt(new DateTimeImmutable());

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($comment);
			$entityManager->flush();

			$this->addFlash('success', 'Votre commentaire a bien été enregistré');

			return $this->redirectToRoute('app_event_show', [
				'id' => $event->getId(),
			]);
		}
	

		return $this->render('event/show.html.twig', [
			'event' => $event,
			'comments' => $comment,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/events/{id<\d+>}/delete", name="app_event_delete", methods={"GET"})
	 * 
	 * @param Event $event
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
