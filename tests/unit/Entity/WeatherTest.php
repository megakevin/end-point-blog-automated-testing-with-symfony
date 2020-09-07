<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Weather;
use App\Entity\WeatherQuery;

class WeatherTest extends TestCase
{
    // build
    public function testBuildAssignsThePrametersToTheCorrectFields()
    {
        // Arrange
        $testWeatherQuery = WeatherQuery::build('New York', 'NY');
        $testApiResponse = [
            'weather' => [
                [
                    'main' => 'testDescription',
                    'description' => 'testSummary'
                ],
            ],
            'main' => [
                'temp' => 12.34,
                'feels_like' => 56.78,
                'temp_min' => 90.12,
                'temp_max' => 34.56,
                'pressure' => 78.90,
                'humidity' => 12.34
            ],
            'visibility' => 12,
            'wind' => [
                'speed' => 34.56,
                'deg' => 78,
                'gust' => 90.12
            ]
        ];

        // Act
        $result = Weather::build($testWeatherQuery, $testApiResponse);

        // Assert
        $this->assertEquals('New York', $result->getCity());
        $this->assertEquals('NY', $result->getState());
        $this->assertEquals('testDescription', $result->getDescription());
        $this->assertEquals('testSummary', $result->getSummary());
        $this->assertEquals(12.34, $result->getTemperature());
        $this->assertEquals(56.78, $result->getFeelsLike());
        $this->assertEquals(90.12, $result->getTempMin());
        $this->assertEquals(34.56, $result->getTempMax());
        $this->assertEquals(78.90, $result->getPressure());
        $this->assertEquals(12.34, $result->getHumidity());
        $this->assertEquals(12, $result->getVisibility());
        $this->assertEquals(34.56, $result->getWindSpeed());
        $this->assertEquals(78, $result->getWindDeg());
        $this->assertEquals(90.12, $result->getWindGust());
    }
}
