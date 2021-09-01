<?php

namespace App\Controller;

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

    /**
     * @Route("/", name="app_home", methods={"GET"})
     *
     * @param Request $request
     * @param CategoryRepository $cr
     * @param EventRepository $er
     *
     * @return Response
     */
    public function home(Request $request, CategoryRepository $cr, EventRepository $er): Response
    {
        $user = $this->getUser();

        // Get top 6 categories
        $topCategories = $cr->findTopCategories();

        // 6 random events ordered by event_date with user location if user is logged in
        if ($user) {
            $randEvents = $er->findRandEvents($user->getCity());
        } else {
            $randEvents = $er->findRandEvents();
        }

        // Get top 6 contributors
        $topContributors = $er->findTopContributors();

        // Init Data for form search, change action to event list to hanfle request
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data, [
            'action' => $this->generateUrl('app_event_list'),
        ]);

        // Handle the form request and use $data in custom query to show searched events
        $form->handleRequest($request);

        return $this->renderForm('home/home.html.twig', [
            'form' => $form,
            'topCategories' => $topCategories,
            'randEvents' => $randEvents,
            'topContributors' => $topContributors,
        ]);
    }
}
