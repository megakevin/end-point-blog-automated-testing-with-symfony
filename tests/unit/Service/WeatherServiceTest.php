<?php

namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use App\Entity\WeatherQuery;
use App\Repository\WeatherQueryRepository;
use App\Service\WeatherApiClient;
use App\Service\WeatherService;

class WeatherServiceTest extends TestCase
{
    // validateWeatherQuery
    public function testValidateWeatherQueryReturnsSuccessWhenGivenValidValues()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);
        $mockApiClient = $this->createMock(WeatherApiClient::class);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->validateWeatherQuery('New York', 'NY');

        // Assert
        $this->assertTrue($result['success']);
    }

    public function testValidateWeatherQueryReturnsAWeatherQueryObjectWithCorrectFieldsWhenGivenValidValues()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);
        $mockApiClient = $this->createMock(WeatherApiClient::class);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->validateWeatherQuery('New York', 'NY');

        // Assert
        $this->assertInstanceOf(WeatherQuery::class, $result['weatherQuery']);
        $this->assertEquals('New York', $result['weatherQuery']->getCity());
        $this->assertEquals('NY', $result['weatherQuery']->getState());
    }

    public function testValidateWeatherQueryDoesNotReturnSuccessWhenNotGivenValidValues()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);
        $mockApiClient = $this->createMock(WeatherApiClient::class);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->validateWeatherQuery('Not a city', 'Not a state');

        // Assert
        $this->assertFalse($result['success']);
    }
}
