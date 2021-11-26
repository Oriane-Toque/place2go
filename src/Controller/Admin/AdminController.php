<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     *
     * @param EventRegistry $eventRepository
     * @param UserRepository $userRepository
     *
     * @return Response
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
        foreach ($dataArr as $value) {
            $data[$value['month'] - 1] = $value['count'];
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

    /**
     * Generate data
     *
     * @Route("/admin/export_data", name="admin_export_data_csv")
     *
     * @param EventRepository $eventRepository
     *
     * @return Response Content-type text/csv
     */
    public function exportDataCsvAction(EventRepository $eventRepository): Response
    {
        // Get events count by month
        $events = $eventRepository->getCountEventsByMonth();

        $rows = array("month,count");
        foreach ($events as $event) {
            $data = array($event['month'], $event['count']);
            $rows[] = implode(',', $data);
        }

        $content = implode("\n", $rows);
        $response = new Response($content);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=export_data_' . date("Y") . '.csv');

        return $response;
    }
}
