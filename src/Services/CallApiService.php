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

        $q = str_replace(' ', '+', $var);


        $response = $this->client->request(
            "GET",
            "https://api-adresse.data.gouv.fr/search/?q={$var}"
            
        );

        return $response->toArray();

    }

}