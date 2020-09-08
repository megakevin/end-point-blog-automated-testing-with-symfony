<?php

namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use App\Entity\WeatherQuery;
use App\Entity\Weather;
use App\Repository\WeatherQueryRepository;
use App\Service\WeatherApiClient;
use App\Service\WeatherService;

class WeatherServiceTest extends TestCase
{
    private $testApiResponse;

    protected function setUp(): void
    {
        $this->testApiResponse = [
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
    }

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

    public function testValidateWeatherQueryReturnsValidationErrorsWhenNotGivenValidValues()
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
        $this->assertCount(1, $result['errors']);
    }

    public function testValidateWeatherQueryReturnsErrorMessageWhenNotGivenValidState()
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
        $this->assertEquals("Please enter a valid US state.", $result['errors'][0]->getMessage());
    }

    public function testValidateWeatherQueryReturnsErrorMessageWhenNotGivenValidCity()
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
        $result = $service->validateWeatherQuery('', 'NY');

        // Assert
        $this->assertEquals("The city should not be blank.", $result['errors'][0]->getMessage());
    }

    public function testValidateWeatherQueryReturnsAWeatherQueryObjectWithCorrectFieldsWhenNotGivenValidValues()
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
        $this->assertInstanceOf(WeatherQuery::class, $result['weatherQuery']);
        $this->assertEquals('Not A City', $result['weatherQuery']->getCity());
        $this->assertEquals('NOT A STATE', $result['weatherQuery']->getState());
    }

    // getCurrentWeather
    public function testGetCurrentWeatherCallsOnTheRepositoryToSaveANewWeatherQuery()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => true,
            'response' => $this->testApiResponse
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Expect
        $mockRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(WeatherQuery $subject) {
                return $subject->getCity() == 'New York' &&
                    $subject->getState() == 'NY';
            }))
        ;

        // Act
        $service->getCurrentWeather('New York', 'NY');
    }

    public function testGetCurrentWeatherCallsOnTheApiClientToGetWeatherData()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => true,
            'response' => $this->testApiResponse
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Expect
        $mockApiClient
            ->expects($this->once())
            ->method('getCurrentWeather')
            ->with('New York', 'NY')
        ;

        // Act
        $service->getCurrentWeather('New York', 'NY');
    }

    public function testGetCurrentWeatherDoesNotReturnSuccessWhenTheApiCallIsUnsuccessful()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => false
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->getCurrentWeather('New York', 'NY');

        // Assert
        $this->assertFalse($result['success']);
    }

    public function testGetCurrentWeatherReturnsAWeatherQueryObjectWithCorrectFieldsWhenTheApiCallIsUnsuccessful()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => false
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->getCurrentWeather('New York', 'NY');

        // Assert
        $this->assertInstanceOf(WeatherQuery::class, $result['weatherQuery']);
        $this->assertEquals('New York', $result['weatherQuery']->getCity());
        $this->assertEquals('NY', $result['weatherQuery']->getState());
    }

    public function testGetCurrentWeatherReturnsSuccessWhenTheApiCallIsSuccessful()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => true,
            'response' => $this->testApiResponse
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->getCurrentWeather('New York', 'NY');

        // Assert
        $this->assertTrue($result['success']);
    }

    public function testGetCurrentWeatherReturnsAWeatherObjectWithCorrectFieldsWhenTheApiCallIsSuccessful()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => true,
            'response' => $this->testApiResponse
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->getCurrentWeather('New York', 'NY');

        // Assert
        $this->assertInstanceOf(Weather::class, $result['weather']);
        $this->assertEquals(
            Weather::build(
                WeatherQuery::build('New York', 'NY'),
                $this->testApiResponse
            ),
            $result['weather']
        );
    }

    public function testGetCurrentWeatherReturnsAWeatherQueryObjectWithCorrectFieldsWhenTheApiCallIsSuccessful()
    {
        // Arrange
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $mockRepository = $this->createMock(WeatherQueryRepository::class);

        $mockApiClient = $this->createMock(WeatherApiClient::class);
        $mockApiClient->method('getCurrentWeather')->willReturn([
            'success' => true,
            'response' => $this->testApiResponse
        ]);

        $service = new WeatherService(
            $validator,
            $mockRepository,
            $mockApiClient
        );

        // Act
        $result = $service->getCurrentWeather('New York', 'NY');

        // Assert
        $this->assertInstanceOf(WeatherQuery::class, $result['weatherQuery']);
        $this->assertEquals('New York', $result['weatherQuery']->getCity());
        $this->assertEquals('NY', $result['weatherQuery']->getState());
    }
}
