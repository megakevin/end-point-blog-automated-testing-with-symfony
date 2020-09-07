<?php

namespace App\Service;

use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\WeatherApiClient;
use App\Repository\WeatherQueryRepository;
use App\Entity\WeatherQuery;
use App\Entity\Weather;

class WeatherService
{
    private $validator;
    private $repository;
    private $apiClient;

    public function __construct(
        ValidatorInterface $validator,
        WeatherQueryRepository $repository,
        WeatherApiClient $apiClient
    ) {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->apiClient = $apiClient;
    }

    public function validateWeatherQuery(string $city, string $state)
    {
        $weatherQuery = WeatherQuery::build($city, $state);

        $errors = $this->validator->validate($weatherQuery);

        if (count($errors) > 0) {
            return [
                'success' => false,
                'errors' => $errors,
                'weatherQuery' => $weatherQuery
            ];
        }

        return [
            'success' => true,
            'weatherQuery' => $weatherQuery
        ];
    }

    public function getCurrentWeather(string $city, string $state)
    {
        $weatherQuery = WeatherQuery::build($city, $state);

        $this->repository->add($weatherQuery);

        $result = $this->apiClient->getCurrentWeather($city, $state);

        if (!$result['success']) {
            return [
                'success' => false,
                'weatherQuery' => $weatherQuery
            ];
        }

        $apiResponse = $result['response'];

        $weather = Weather::build($weatherQuery, $apiResponse);

        return [
            'success' => true,
            'weather' => $weather,
            'weatherQuery' => $weatherQuery
        ];
    }
}
