<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public function getApi(string $var): array
    {
        $var = implode(',', array_unique(explode(', ', $var)));
        $var = str_replace(" ", "+", $var);
        $var = str_replace("-", "+", $var);
        $var = strtolower($var);

        $response = $this->client->request(
            "GET",
            "https://api.mapbox.com/geocoding/v5/mapbox.places/{$var}.json?access_token=pk.eyJ1Ijoia2V5Z2VuOSIsImEiOiJja3NrNWh6MGQwczZnMnBsNHhqYnRtMDUxIn0.dq2MMs1vSwGk8nMIj9NTxQ"
        );

        $array = $response->toArray();

        return $array['features'][0]['geometry']['coordinates'];
    }
}
