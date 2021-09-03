<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 */
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

        // Get events count by month
        $dataArr = $eventRepository->getCountEventsByMonth();

        // Init data array
        $data = array_fill(0, 12, 0);

        // Populate array at good place
        foreach($dataArr as $value)
        {
            $data[$value['month']-1] = $value['count'];
        }

        $datasets = (object) array(
            'label' => 'Nbre de sorties par mois en 2021',
            'data' => $data
        );

        return $this->render('admin/home.html.twig', [
            'countArr' => $countArr,
            'datasets' => $datasets
        ]);
    }
}
