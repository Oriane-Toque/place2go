<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Sort;

class HomeController extends AbstractController
{
    private $sort;

    public function __construct(Sort $sort){
        $this->sort = $sort;
    }    

    /**
		 * Return & display Home page with top 6 cities & categories
		 * 
     * @Route("/", name="app_home")
     */
    public function home(CategoryRepository $cr, EventRepository $er): Response
    {
				// top 6 categories -> meilleur score events (+ récupérer nbr event)
				$topCategories = $cr->findTopCategories();

				// top 6 cities -> meilleur score events (+ récupérer nbr event)
				$topCities = $er->findTopCities();
        
				dump($topCities);
        return $this->render('home/home.html.twig', [
            'topCategories' => $topCategories,
            'topCities' => $topCities,
        ]);
    }
}
