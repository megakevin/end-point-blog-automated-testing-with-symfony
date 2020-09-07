<?php

namespace App\Tests\Unit\Entity;

use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use App\Entity\WeatherQuery;

class WeatherQueryTest extends TestCase
{
    // build
    public function testBuildAssignsTheParametersToTheCorrectFields()
    {
        // Arrange
        $testCity = 'MyCity';
        $testState = 'ST';

        // Act
        $result = WeatherQuery::build($testCity, $testState);

        // Assert
        $this->assertEquals($testCity, $result->getCity());
        $this->assertEquals($testState, $result->getState());
    }

    public function testBuildCapitalizesTheGivenCityParameter()
    {
        // Arrange
        $testCity = 'my city';
        $testState = 'ST';

        // Act
        $result = WeatherQuery::build($testCity, $testState);

        // Assert
        $this->assertEquals('My City', $result->getCity());
    }

    public function testBuildCapitalizesTheGivenStateParameter()
    {
        // Arrange
        $testCity = 'My City';
        $testState = 'st';

        // Act
        $result = WeatherQuery::build($testCity, $testState);

        // Assert
        $this->assertEquals('ST', $result->getState());
    }

    public function testBuildSetsTheCurrentMomentAsTheCreatedField()
    {
        // Arrange
        $testCity = 'My City';
        $testState = 'ST';

        // Act
        $result = WeatherQuery::build($testCity, $testState);

        // Assert
        $this->assertEquals(
            (new DateTime())->getTimestamp(),
            $result->getCreated()->getTimestamp(),
            '', 1
        );
    }

    // Validation rules
    /**
     * @dataProvider getValidationTestCases
     */
    public function testValidation($city, $state, $expected)
    {
        // Arrange
        $subject = WeatherQuery::build($city, $state);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        // Act
        $result = $validator->validate($subject);

        // Assert
        $this->assertEquals($expected, count($result) == 0);
    }

    public function getValidationTestCases()
    {
        return [
            'Succeeds when data is correct' => [ 'New York', 'NY', true ],
            'Fails when city is missing' => [ '', 'NY', false ],
            'Fails when state is missing' => [ 'New York', '', false ],
            'Fails when state is not a valid US state' => [ 'New York', 'AAA', false ],
        ];
    }
}
