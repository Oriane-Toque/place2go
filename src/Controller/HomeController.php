<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(EventRepository $er): Response
    {
        $topCities = $er->findPopularCities(5);
        dd($topCities);
        
        return $this->render('home/home.html.twig', [
            'topCities' => $topCities
        ]);
    }


}
