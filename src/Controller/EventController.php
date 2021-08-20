<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Event;
use App\Form\EventType;
use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use App\Repository\AttendantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EventController extends AbstractController
{
    /**
     * @Route("/events", name="app_event_list", methods={"GET"})
     */
    public function list(Request $request, EventRepository $eventRepository): Response
    {    
        // Init Data to handle form search
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);

        // Handle the form request and use $data in custom query to show searched events
        $form->handleRequest($request);
        $events = $eventRepository->findSearch($data);

        return $this->render('event/list.html.twig', [
            'events' => $events,
            'form' => $form->createView()
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
		public function searchByCategory(Category $category, EventRepository $er): Response {

			if(null === $category) {
				throw $this->createNotFoundException('404 - Catégorie introuvable');
			}

			if($this->getUser()) {

				$city = $this->getUser()->getCity();

				$events = $er->findEventsByCategory($category->getId(), $city);

				return $this->render('event/list.html.twig', [
					'events' => $events,
				]);
			}

			$events = $er->findEventsByCategory($category->getId());

			return $this->render('event/list.html.twig', [
				'events' => $events,
			]);
		}

		/**
		 * Return all events for one city
		 *
		 * @Route("events/city/{slug}/search", name="app_events_city_search", methods={"GET"})
		 * 
		 * @return Response renvoie sur la page liste filtré selon la ville sélectionnée
		 */
		public function searchByCities(EventRepository $er) {

			// TODO il nous faut slugifier le nom de la ville pour injection dans url
			// on fait un requete custom pour rechercher toutes les sorties en fonction
			// du slug de la ville
			// on renvoit dans la vue
		}

    /**
     * @Route("/events/{id<\d+>}/show", name="app_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/events/create", name="app_event_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        // New object
        $event = New Event();

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
		 * TODO optimiser la méthode pour éviter de passer par l'id
		 * 
     * @Route("/events/{id<\d+>}/delete", name="app_event_delete", methods={"GET"})
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

		/**
		 * To leave an event
		 *
		 * @Route("/event/{id<\d+>}/leave", name="app_event_leave", methods={"GET"})
		 */
		public function leave(Event $event = null, AttendantRepository $ar, EntityManagerInterface $em, Request $request) {

			$user = $this->getUser();

			if(null === $event) {
				throw $this->createNotFoundException('404 - Sortie introuvable !');
			}

			// récupération de la participation de l'utilisateur selon l'évènement qu'il a sélectionné
			// requete custom pour la récupération
			$attendant = $ar->findByUserEvent($user, $event);

			// suppresion de la participation à la sortie de la bdd
			$em->remove($attendant[0]);
			$em->flush();

			// Flash message
			$this->addFlash('success', 'Vous avez quitté la sortie avec succès !');

			// redirection dans la page courante
			return $this->redirect($request->headers->get('referer'));
		}

}
