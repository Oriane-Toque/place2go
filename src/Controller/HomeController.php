<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(EventRepository $er, CategoryRepository $cr): Response
    {
        $topCities = $er->findPopularCities(5);
        $categoriesList = $cr->findAllCategories();
        $topCategories = $this->sortCategories($categoriesList);

        return $this->render('home/home.html.twig', [
            'topCities' => $topCities,
            'topCategories' => $topCategories
        ]);
    }

    public function sortCategories($categoriesList)
    {
        $categoriesOrder = [];
        foreach ($categoriesList as $category) {
            $name = $category->getName();
            $events = count($category->getEvents());
            $categoriesOrder[$name] = $events;
        }
        arsort($categoriesOrder);

        $topCategories = array_slice($categoriesOrder, 0, 5, true);
        return $topCategories;
    }
}
