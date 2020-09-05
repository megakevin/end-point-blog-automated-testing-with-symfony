<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiClient
{
    private $httpClient;

    public function __construct(
        HttpClientInterface $httpClient
    ) {
        $this->httpClient = $httpClient;
    }

    public function getCurrentWeather(string $city, string $state)
    {
        $response = $this->httpClient->request(
            'GET',
            'http://api.openweathermap.org/data/2.5/weather?q=' . $city . ',' . $state . ',us&appid=fc47b8e275a1fe6f31f7ffe3fef615d8'
        );

        if ($response->getStatusCode() != 200) {
            return [
                'success' => false,
            ];
        }

        $response = json_decode($response->getContent(), true);

        return [
            'success' => true,
            'response' => $response,
        ];
    }
}
