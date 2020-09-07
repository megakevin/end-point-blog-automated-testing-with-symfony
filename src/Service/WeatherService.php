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

    public function validateWeatherQuery(array $formData)
    {
        $weatherQuery = WeatherQuery::buildFromFormData($formData);

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

        $weather = (new Weather())
            ->setCity($weatherQuery->getCity())
            ->setState($weatherQuery->getState())
            ->setDescription($apiResponse["weather"][0]["main"])
            ->setSummary($apiResponse["weather"][0]["description"])
            ->setTemperature($apiResponse["main"]["temp"])
            ->setFeelsLike($apiResponse["main"]["feels_like"])
            ->setTempMin($apiResponse["main"]["temp_min"])
            ->setTempMax($apiResponse["main"]["temp_max"])
            ->setPressure($apiResponse["main"]["pressure"])
            ->setHumidity($apiResponse["main"]["humidity"])
            ->setVisibility($apiResponse["visibility"])
            ->setWindSpeed($apiResponse["wind"]["speed"])
            ->setWindDeg($apiResponse["wind"]["deg"] ?? 0)
            ->setWindGust($apiResponse["wind"]["gust"] ?? 0)
        ;

        return [
            'success' => true,
            'weather' => $weather,
            'weatherQuery' => $weatherQuery
        ];
    }
}
