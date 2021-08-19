<?php

namespace App\Services;

class Sort
{

    public function allCategories($categoriesList)
    {
        $categoriesOrder = [];
        foreach ($categoriesList as $category) {
            $name = $category->getName();
            $events = count($category->getEvents());
            $categoriesOrder[$name] = $events;
        }
        arsort($categoriesOrder);
        return $categoriesOrder;
    }

    public function sliceCategories($categoriesList)
    {
        $categoriesOrder = $this->allCategories($categoriesList);
        $topCategories = array_slice($categoriesOrder, 0, 5, true);
        return $topCategories;
    }
}
