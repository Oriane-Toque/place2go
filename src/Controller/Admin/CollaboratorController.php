<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class CollaboratorController extends AbstractController
{
    /**
     * @Route("/admin/collaborators", name="admin_collaborator_list", methods={"GET"})
     */
    public function list(UserRepository $userRepository): Response
    {    
        // Find all collaborators => (users with roles = '["ROLE_ADMIN"]')
        $users = $userRepository->findCollaborators();

        return $this->render('admin/collaborator/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/collaborators/{id<\d+>}/show", name="admin_collaborator_show", methods={"GET"})
     */
    public function show(User $user, EventRepository $eventRepository): Response
    {
        // Last events organise by this user
		$userLastEvents = $eventRepository->findLastAuthorEvents($user->getId(), 3);

		// Last events in which the user participates
		$userLastExits = $eventRepository->findLastAttendantEvents($user->getId(), 3);

        return $this->render('admin/collaborator/show.html.twig', [
            'user' => $user,
            'userLastEvents' => $userLastEvents,
            'userLastExits' => $userLastExits,
        ]);
    }

    /**
     * @Route("/admin/collaborators/create", name="admin_collaborator_create", methods={"GET", "POST"})
     */
    public function create(Request $request, FileUploader $fileUploader): Response
    {
        // New object
        $user = New User();

        // Create new form associated to entity
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imgFile */
            $imgFile = $form->get('avatar')->getData();

            // this condition is needed because the 'avatar' field is not required
            // so the image file must be processed only when a file is uploaded
            if ($imgFile) {
                $imgFilename = $fileUploader->upload($imgFile, $this->getParameter('avatar_directory'));

                // updates the 'avatar' property to store the image file name
                $user->setAvatar($imgFilename);
            }

            // Add the role ROLE_USER
            $user->setRoles(["ROLE_ADMIN"]);

            // Persist in BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Utilisateur créé avec succès !');

            return $this->redirectToRoute('admin_collaborator_list');
        }

        return $this->render('admin/collaborator/create.html.twig', [
            'form' => $form->createView(),
        ]);
                
    }

    /**
     * @Route("/admin/collaborators/{id<\d+>}/edit", name="admin_collaborator_edit", methods={"GET", "POST"})
     */
    public function edit(User $user, Request $request): Response
    {
        // Create new form associated to entity
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            // No persist on edit
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Utilisateur modifié avec succès !');

            return $this->redirectToRoute('admin_collaborator_list');
        }

        return $this->render('admin/collaborator/edit.html.twig', [
            'form' => $form->createView(),
        ]);
                 
    }

    /**
     * @Route("/admin/collaborators/{id<\d+>}/delete", name="admin_collaborator_delete", methods={"GET"})
     */
    public function delete(User $user): Response
    {
        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        // Flash message
        $this->addFlash('success', 'Utilisateur supprimé avec succès');

        return $this->redirectToRoute('admin_collaborator_list');
    }

    /**
     * @Route("/admin/collaborators/{id<\d+>}/desactive", name="admin_collaborator_desactive", methods={"GET"})
     */
    public function desactive(User $user): Response
    {
        // Set IsActive to 0
        $user->setIsActive(0);

        $entityManager = $this->getDoctrine()->getManager();
        // No persist on edit
        $entityManager->flush();

        // Flash message
        //$this->addFlash('success', 'Utilisateur '. $user->getId() . ' a été désactivé !');

        return $this->redirectToRoute('admin_collaborator_show', ['id' => $user->getId()]);
            
    }

    /**
     * @Route("/admin/collaboratorss/{id<\d+>}/active", name="admin_collaborator_active", methods={"GET"})
     */
    public function active(User $user): Response
    {
        // Set IsActive to 1
        $user->setIsActive(1);

        $entityManager = $this->getDoctrine()->getManager();
        // No persist on edit
        $entityManager->flush();

        // Flash message
        //$this->addFlash('success', 'Utilisateur '. $user->getId() . ' a été activé !');

        return $this->redirectToRoute('admin_collaborator_show', ['id' => $user->getId()]);
            
    }
}