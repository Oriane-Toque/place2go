<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Services\FileUploader;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_category_list", methods={"GET"})
     *
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     */
    public function list(CategoryRepository $categoryRepository): Response
    {
        // Find all categories
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/categories/{id<\d+>}/show", name="admin_category_show", methods={"GET"})
     *
     * @param Category $category
     *
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/admin/categories/create", name="admin_category_create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     *
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader): Response
    {
        // New object
        $category = new Category();

        // Create new form associated to entity
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        dump($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imgFile */
            $imgFile = $form->get('picture')->getData();

            // this condition is needed because the 'picture' field is not required
            // so the image file must be processed only when a file is uploaded
            if ($imgFile) {
                $imgFilename = $fileUploader->upload($imgFile, $this->getParameter('category_directory'));

                // updates the 'picture' property to store the image file name
                $category->setPicture($imgFilename);
            }

            // Persist in BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Catégorie créée avec succès !');

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->renderForm('admin/category/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/admin/categories/{id<\d+>}/edit", name="admin_category_edit", methods={"GET", "POST"})
     *
     * @param Category $category
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Category $category, Request $request): Response
    {
        // Create new form associated to entity
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // No persist on edit
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Catégorie modifiée avec succès !');

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->renderForm('admin/category/edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/categories/{id<\d+>}/delete", name="admin_category_delete", methods={"GET"})
     *
     * @param Category $category
     *
     * @return Response
     */
    public function delete(Category $category): Response
    {
        // Remove from BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        // Flash message
        $this->addFlash('success', 'Catégorie supprimée avec succès');

        return $this->redirectToRoute('admin_category_list');
    }
}
