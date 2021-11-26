<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_list", methods={"GET"})
     *
     * @param CommentRpository $commentRepository
     *
     * @return Response
     */
    public function list(CommentRepository $commentRepository): Response
    {
        // Find all comments
        $comments = $commentRepository->findAll();

        return $this->render('admin/comment/list.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/admin/comments/{id<\d+>}/show", name="admin_comment_show", methods={"GET"})
     *
     * @param Comment $comment
     *
     * @return Response
     */
    public function show(Comment $comment): Response
    {
        return $this->render('admin/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/admin/comments/create", name="admin_comment_create", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        // New object
        $comment = new Comment();

        // Create new form associated to entity
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Persist in BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Commentaire créé avec succès !');

            return $this->redirectToRoute('admin_comment_list');
        }

        return $this->renderForm('admin/comment/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/admin/comments/{id<\d+>}/edit", name="admin_comment_edit", methods={"GET", "POST"})
     *
     * @param Comment $comment
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Comment $comment, Request $request): Response
    {
        // Create new form associated to entity
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // No persist on edit
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Commentaire modifié avec succès !');

            return $this->redirectToRoute('admin_comment_list');
        }

        return $this->renderForm('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form
        ]);
    }

    /**
     * @Route("/admin/comments/{id<\d+>}/delete", name="admin_comment_delete", methods={"GET"})
     *
     * @param Comment $comment
     *
     * @return Response
     */
    public function delete(Comment $comment): Response
    {
        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        // Flash message
        $this->addFlash('success', 'Commentaire supprimé avec succès');

        return $this->redirectToRoute('admin_comment_list');
    }
}
