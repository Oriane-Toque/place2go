<?php

namespace App\Controller;

use App\Entity\Attendant;
use App\Entity\Event;
use App\Repository\AttendantRepository;
use App\Services\isAttendant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AttendantController extends AbstractController
{
    private $attendantRepository;
    private $em;
    private $isAttendant;

    public function __construct(AttendantRepository $attendantRepository, EntityManagerInterface $em, isAttendant $isAttendant)
    {
        $this->attendantRepository = $attendantRepository;
        $this->em = $em;
        $this->isAttendant = $isAttendant;
    }


    /**
     * To join an event
     * @Route("/event/{id<\d+>}/join", name="app_event_join", methods={"GET"})
     * 
     * @param Event $event
     * @param Request $request
     * 
     * @return Response
     */
    public function join(Event $event, Request $request): Response
    {
        $user = $this->getUser();

        //? Vérifier si il y a une place disponible
        // je récupère le max des participants
        $maxAttendant = $event->getMaxAttendants();
        // je récupère le nbr des participants actuels
        $nbrAttendants = count($event->getAttendants());

        //? Vérifier si l'utilisateur n'est pas déjà participant
        // je récupère la liste des participants de la sortie sélectionnée
        $attendantList = $this->attendantRepository->findByEvent($event);

        // je fais appel à mon service qui me permet de vérifier si
        // l'utilisateur participe déjà à cette sortie
        $isAttendant = $this->isAttendant->checkIsAttendant($attendantList, $user);

        //? Vérifier si l'utilisateur est connecté
        if ($user) {
            // On vérifie si il y a des places de disponible
            if ($nbrAttendants < $maxAttendant) {

                // Si il n'est pas déjà un participant
                if ($isAttendant === false) {
                    $attendant = new Attendant();

                    $attendant->setUser($user);
                    $attendant->setEvent($event);

                    $this->em->persist($attendant);
                    $this->em->flush();

                    // Flash message
                    $this->addFlash('success', 'Vous avez bien été ajouté à la sortie ' . $event->getTitle() . ' !');

                    // Redirection sur la page de l'évènement correspondant
                    return $this->redirectToRoute('app_event_show', [
                        'id' => $event->getId(),
                    ]);
                }
                // on indique à l'utilisateur qu'il participe déjà à cette sortie
                $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie !');

                return $this->redirect($request->headers->get('referer'));
            }

            // on indique à l'utilisateur qu'il n'y a plus de places disponibles
            $this->addFlash('danger', 'Désolé il n\'y a plus de places disponibles sur cette sortie !');

            return $this->redirect($request->headers->get('referer'));
        }

        // on lui indique qu'il est nécessaire de se connecter pour participer à une sortie
        $this->addFlash('danger', 'Connectez vous pour participer à une sortie !');

        return $this->redirectToRoute('app_login');
    }

    /**
     * To leave an event
     * @Route("/event/{id<\d+>}/leave", name="app_event_leave", methods={"GET"})
     * 
     * @param Event $event
     * @param AttendantRepository $ar
     * @param EntityManagerInterface $em
     * @param Request $request
     * 
     * @return Response
     */
    public function leave(Event $event, Request $request): Response
    {
        $user = $this->getUser();

        // récupération de la participation de l'utilisateur selon l'évènement qu'il a sélectionné
        // requete custom pour la récupération
        $attendant = $this->attendantRepositoryar->findByUserEvent($user, $event);

        //? Vérifier si l'utilisateur est un participant
        // je récupère la liste des participants de la sortie sélectionnée
        $attendantList = $this->attendantRepository->findByEvent($event);

        // je fais appel à mon service qui me permet de vérifier si
        // l'utilisateur participe déjà à cette sortie
        $isAttendant = $this->isAttendant->checkIsAttendant($attendantList, $user);

        if ($user) {

            if ($isAttendant) {
                // suppresion de la participation à la sortie de la bdd
                $this->em->remove($attendant[0]);
                $this->em->flush();

                // Flash message
                $this->addFlash('success', 'Vous avez quitté la sortie avec succès !');

                // redirection dans la page courante
                return $this->redirect($request->headers->get('referer'));
            }
            // on indique à l'utilisateur qu'il ne participe pas à cette sortie
            $this->addFlash('warning', 'Vous n\'êtes pas inscrit à cette sortie !');

            return $this->redirect($request->headers->get('referer'));
        }
        // on indique à l'utilisateur qu'il doit se connecter
        $this->addFlash('danger', 'Vous devez vous connecter pour effetuer cette action !');

        return $this->redirect($request->headers->get('referer'));
    }
}
