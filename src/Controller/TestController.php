<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Services\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(Request $request, CallApiService $callApiService): Response
    {
           // New object
           $event = New Event();

           // Create new form associated to entity
           $form = $this->createForm(EventType::class, $event);
           $form->handleRequest($request);
   
           if ($form->isSubmitted() && $form->isValid()) {
               $event->setAuthor($this->getUser());
               // Add coordinates to Event
               
               $test = $callApiService->getApi($form['address']->getData());
               

               $event->setLon($test['features'][0]['geometry']['coordinates'][0]);
               $event->setLat($test['features'][0]['geometry']['coordinates'][1]);
               
               
               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($event);
               $entityManager->flush();
   
               // Flash message
               $this->addFlash('success', 'Sortie créée avec succès !');
   
               return $this->redirectToRoute('test', []);
           }



        return $this->render('test/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
