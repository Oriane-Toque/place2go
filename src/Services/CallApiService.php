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
        $response = $this->client->request(
            "GET",
            "https://api-adresse.data.gouv.fr/search/?q={$var}"
        );

        $array = $response->toArray();

        return $array['features'][0]['geometry']['coordinates'];
    }
}