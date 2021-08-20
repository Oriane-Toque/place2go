<?php

namespace App\Controller;

use App\Services\Sort;
use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
				// top 6 categories -> meilleur score events
				$topCategories = $cr->findTopCategories();

				// top 6 cities -> meilleur score events
				$topCities = $er->findTopCities();

				// top 6 contributors -> meilleur score events
        $topContributors = $er->findTopContributors();

        return $this->render('home/home.html.twig', [
            'topCategories' => $topCategories,
            'topCities' => $topCities,
            'topContributors' => $topContributors,

        ]);
    }
}
