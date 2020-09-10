<?php

namespace App\Tests\Functional\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\WeatherApiClient;

class WeatherApiClientTest extends KernelTestCase
{
    public function testGetCurrentWeatherInvokesTheApiAndReturnsTheExpectedResponse()
    {
        // Arrange
        self::bootKernel();
        $apiClient = self::$container->get(WeatherApiClient::class);

        // Act
        $result = $apiClient->getCurrentWeather('New York', 'NY');

        // Assert
        $this->assertArrayHasKey('weather', $result['response']);
        $this->assertGreaterThanOrEqual(1, $result['response']['weather']);
        $this->assertArrayHasKey('main', $result['response']['weather'][0]);
        $this->assertArrayHasKey('description', $result['response']['weather'][0]);
        
        $this->assertArrayHasKey('main', $result['response']);
        $this->assertArrayHasKey('temp', $result['response']['main']);
        $this->assertArrayHasKey('feels_like', $result['response']['main']);
        $this->assertArrayHasKey('temp_min', $result['response']['main']);
        $this->assertArrayHasKey('temp_max', $result['response']['main']);
        $this->assertArrayHasKey('pressure', $result['response']['main']);
        $this->assertArrayHasKey('humidity', $result['response']['main']);

        $this->assertArrayHasKey('visibility', $result['response']);

        $this->assertArrayHasKey('wind', $result['response']);
        $this->assertArrayHasKey('speed', $result['response']['wind']);
        $this->assertArrayHasKey('deg', $result['response']['wind']);
    }
}
