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
        $topCities = $er->findPopularCities(5);
        $allCities = $er->findAllCities();
        $categoriesList = $cr->findAllCategories();
        
        return $this->render('home/home.html.twig', [
            'topCities' => $topCities,
            'cityList' => $allCities,
            'allCategories' => $this->sort->allCategories($categoriesList),
            'topCategories' => $this->sort->sliceCategories($categoriesList)
        ]);
    }
}
