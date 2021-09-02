<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="app_category_list", methods={"GET"})
     */
    public function list(): Response
    {
        return $this->render('category/list.html.twig', [
            "categories" => $this->getDoctrine()->getRepository(Category::class)->findAll(),
        ]);
    }
}
