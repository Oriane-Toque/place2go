<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_category_list", methods={"GET"})
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
     */
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/admin/categories/create", name="admin_category_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        // New object
        $category = New Category();

        // Create new form associated to entity
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Catégorie créée avec succès !');

            return $this->redirectToRoute('admin_category_show', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
                
    }

    /**
     * @Route("/admin/categories/{id<\d+>}/edit", name="admin_category_edit", methods={"GET", "POST"})
     */
    public function edit(Category $category, Request $request): Response
    {
        // Create new form associated to entity
        $form = $this->createForm(EventType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            // No persist on edit
            $entityManager->flush();

            // Flash message
            $this->addFlash('success', 'Catégorie modifiée avec succès !');

            return $this->redirectToRoute('admin_category_show', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
                 
    }

    /**
     * @Route("/admin/categories/{id<\d+>}/delete", name="admin_category_delete", methods={"GET"})
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