<?php

namespace App\Tests\Unit\Entity;

use DateTime;
use PHPUnit\Framework\TestCase;
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

    // buildFromFormData
    public function testBuildFromFormDataAssignsTheParametersToTheCorrectFields()
    {
        // Arrange
        $testCity = 'MyCity';
        $testState = 'ST';

        // Act
        $result = WeatherQuery::buildFromFormData([
            'city' => $testCity,
            'state' => $testState
        ]);

        // Assert
        $this->assertEquals($testCity, $result->getCity());
        $this->assertEquals($testState, $result->getState());
    }

    public function testBuildFromFormDataSetsTheCurrentMomentAsTheCreatedField()
    {
        // Arrange
        $testCity = 'My City';
        $testState = 'ST';

        // Act
        $result = WeatherQuery::buildFromFormData([
            'city' => $testCity,
            'state' => $testState
        ]);

        // Assert
        $this->assertEquals(
            (new DateTime())->getTimestamp(),
            $result->getCreated()->getTimestamp(),
            '', 1
        );
    }
}
