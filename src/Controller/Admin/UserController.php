<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Services\FileUploader;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_user_list", methods={"GET"})
     *
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function list(UserRepository $userRepository): Response
    {
        // Find all users
        $users = $userRepository->findUsersByRole('["ROLE_USER"]');

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/{id<\d+>}/show", name="admin_user_show", methods={"GET"})
     *
     * @param User $user
     * @param EventRepository $eventRepository
     *
     * @return Response
     */
    public function show(User $user, EventRepository $eventRepository): Response
    {
        // Last events organise by this user
        $userLastEvents = $eventRepository->findLastAuthorEvents($user->getId(), 3);

        // Last events in which the user participates
        $userLastExits = $eventRepository->findLastAttendantEvents($user->getId(), 3);

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'userLastEvents' => $userLastEvents,
            'userLastExits' => $userLastExits,
        ]);
    }

    /**
     * @Route("/admin/users/{id<\d+>}/show/floating", name="admin_user_show_floating", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function showFloating(User $user = null): Response
    {
        // 404 ?
        if ($user === null) {
            return $this->json(["message" => "Utilisateur non trouvé"], Response::HTTP_NOT_FOUND);
        }

        // Age calcul
        $age = date_diff(date_create(date_format($user->getBirthday(), 'Y-m-d')), date_create(date("Y-m-d")));

        $data = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'nickname' => $user->getNickname(),
            'avatar' => $user->getAvatar(),
            'city' => $user->getCity(),
            'birthday' => $age->format('%y') . ' ans',
            'createdAt' => date_format($user->getCreatedAt(), 'd/m/Y'),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/admin/users/create", name="admin_user_create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     *
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader): Response
    {
        // New object
        $user = new User();

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
            $user->setRoles(["ROLE_USER"]);

            // Persist in BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Utilisateur créé avec succès !');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/{id<\d+>}/edit", name="admin_user_edit", methods={"GET", "POST"})
     *
     * @param User $user
     * @param Request $request
     *
     * @return Response
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

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/{id<\d+>}/delete", name="admin_user_delete", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function delete(User $user): Response
    {
        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        // Flash message
        $this->addFlash('success', 'Utilisateur supprimé avec succès');

        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/admin/users/{id<\d+>}/desactive", name="admin_user_desactive", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function desactive(User $user): Response
    {
        // Set IsActive to 0
        $user->setIsActive(0);

        $entityManager = $this->getDoctrine()->getManager();
        // No persist on edit
        $entityManager->flush();

        return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
    }

    /**
     * @Route("/admin/users/{id<\d+>}/active", name="admin_user_active", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function active(User $user): Response
    {
        // Set IsActive to 1
        $user->setIsActive(1);

        $entityManager = $this->getDoctrine()->getManager();
        // No persist on edit
        $entityManager->flush();

        return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
    }
}
