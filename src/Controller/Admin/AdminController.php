<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function home(EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        // Get counts for dashboard
        $countArr['events']         = $eventRepository->getTotalEvents();
        $countArr['events_to_come'] = $eventRepository->getTotalEventsToCome();
        $countArr['users']          = $userRepository->getTotalUsers();
        $countArr['active_users']   = $userRepository->getTotalActiveUsers();

        return $this->render('admin/home.html.twig', [
            'countArr' => $countArr,
        ]);
    }
}
