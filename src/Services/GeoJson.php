<?php

namespace App\Services;

class GeoJson
{
    public function createGeoJson($events)
    {
        $geojson = ['type' => 'FeatureCollection', 'features' => []];

        foreach ($events as $place) {
            $marker = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        floatval($place->getLat()),
                        floatval($place->getLon()),
                    ]
                ],

                'properties' => [
                    'title' => $place->getTitle(),
                    'description' => $place->getDescription(),
                ]
            ];
            array_push($geojson['features'], $marker);
        };

        return $geojson;
    }
}
