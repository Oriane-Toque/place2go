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
     * @Route("/", name="app_home")
     */
    public function home(EventRepository $er, CategoryRepository $cr): Response
    {
				// top 6 categories -> meilleur score events (+ récupérer nbr event)
				$topCategories = $cr->findTopCategories();

				// it works
				dd($topCategories);
				
				// top 6 villes -> meilleur score event (+ récupérer nbr event)
        
        return $this->render('home/home.html.twig', [
            'topCategories' => $topCategories,
        ]);
    }
}
