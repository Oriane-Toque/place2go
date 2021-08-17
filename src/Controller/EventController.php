<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events", methods={"GET"})
     */
    public function list(EventRepository $eventRepository): Response
    {
        // Récupération des films
        /*$movies = $movieRepository->findBy(
            [], // Condition WHERE => aucune
            ['releaseDate' => 'DESC']
        );*/

        //$movies = $movieRepository->findByTitleOrderedByASC();
        $events = $eventRepository->findAll();

        //dd($events);

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

}
