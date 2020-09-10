<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiClient
{
    private $openWeatherMapAppId;
    private $httpClient;

    public function __construct(
        string $openWeatherMapAppId,
        HttpClientInterface $httpClient
    ) {
        $this->openWeatherMapAppId = $openWeatherMapAppId;
        $this->httpClient = $httpClient;
    }

    public function getCurrentWeather(string $city, string $state)
    {
        $response = $this->httpClient->request(
            'GET',
            'http://api.openweathermap.org/data/2.5/weather?q=' . $city . ',' . $state . ',us&appid=' . $this->openWeatherMapAppId
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
