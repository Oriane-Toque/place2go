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
	 * Return & display Home page with top 6 cities & categories
	 * 
	 * @Route("/", name="app_home")
	 */
	public function home(Request $request, CategoryRepository $cr, EventRepository $er): Response
	{
		// récupération de l'utilisateur
		$user = $this->getUser();

		// dd($user->getCity());
		// top 6 categories -> meilleur score events
		$topCategories = $cr->findTopCategories();

		// 6 random events order by event date
		// optionnel - en fonction de la ville enregistrée sur le compte utilisateur
    if ($user) {
        $randEvents = $er->findRandEvents($user->getCity());
    } else {
			$randEvents = $er->findRandEvents();
		}

		// top 6 contributors -> meilleur score events
		$topContributors = $er->findTopContributors();

		// Init Data for form search, change action to event list to hanfle request
		$data = new SearchData();
		$form = $this->createForm(SearchFormType::class, $data, [
			'action' => $this->generateUrl('app_event_list'),
		]);

		// Handle the form request and use $data in custom query to show searched events
		$form->handleRequest($request);

		return $this->render('home/home.html.twig', [
			'topCategories' => $topCategories,
			'randEvents' => $randEvents,
			'topContributors' => $topContributors,
			'form' => $form->createView(),
		]);
	}
}
