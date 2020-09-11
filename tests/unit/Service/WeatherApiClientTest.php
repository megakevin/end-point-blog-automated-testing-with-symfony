<?php

namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\Service\WeatherApiClient;

class WeatherApiClientTest extends TestCase
{
    public function testGetCurrentWeatherCallsOnTheHttpClientToMakeAGetRequestToTheWeatherApi()
    {
        // Arrange
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(500);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        $apiClient = new WeatherApiClient(
            'test_app_id',
            $mockHttpClient
        );
        
        // Expect
        $mockHttpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'http://api.openweathermap.org/data/2.5/weather?q=test_city,test_state,us&appid=test_app_id'
            )
        ;

        // Act
        $result = $apiClient->getCurrentWeather('test_city', 'test_state');
    }

    public function testGetCurrentWeatherDoesNotReturnSuccessIfTheResponseStatusCodeIsNot200()
    {
        // Arrange
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(500);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        $apiClient = new WeatherApiClient(
            'test_app_id',
            $mockHttpClient
        );

        // Act
        $result = $apiClient->getCurrentWeather('test_city', 'test_state');

        // Assert
        $this->assertFalse($result['success']);
    }

    public function testGetCurrentWeatherReturnsSuccessAndResponseDataAsArrayIfTheResponseStatusCodeIs200()
    {
        // Arrange
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getContent')->willReturn('{some_filed: "some_value"}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        $apiClient = new WeatherApiClient(
            'test_app_id',
            $mockHttpClient
        );

        // Act
        $result = $apiClient->getCurrentWeather('test_city', 'test_state');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(
            json_decode('{some_filed: "some_value"}', true),
            $result['response']
        );
    }
}
