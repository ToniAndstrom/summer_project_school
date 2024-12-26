<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiServiceWeather
{
    private $httpClient;
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    public function getLocations(): array
    {
        $response = $this->httpClient->request("GET", "https://api.weatherapi.com/v1/current.json");
        return $response->toArray();
    }
}