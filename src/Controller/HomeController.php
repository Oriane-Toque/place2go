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
     * @Route("/", name="app_home")
     */
    public function home(Request $request, EventRepository $er, CategoryRepository $cr): Response
    {
        $topCities = $er->findPopularCities(5);
        $allCities = $er->findAllCities();
        $categoriesList = $cr->findAllCategories();

        // Init Data to handle form search
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);

        // Handle the form request and use $data in custom query to show searched events
        $form->handleRequest($request);
        
        
        return $this->render('home/home.html.twig', [
            'topCities' => $topCities,
            'cityList' => $allCities,
            'allCategories' => $this->sort->allCategories($categoriesList),
            'topCategories' => $this->sort->sliceCategories($categoriesList),
            'form' => $form->createView(),
        ]);
    }
}
