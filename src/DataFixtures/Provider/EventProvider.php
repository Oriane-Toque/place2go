<?php

namespace App\DataFixtures\Provider;

class EventProvider
{
    private $categories = [
        'Cinéma',
        'Restaurant',
        'Balade',
        'Randonnée',
        'Sport',
        'Culturel',
        'Concert',
        'Bar',
        'Activité de groupe',
        'Voyage'
    ];

    
    /**
     * Random Category
     */
    public function eventCategory()
    {
        return $this->categories[array_rand($this->categories)];
    }
}
