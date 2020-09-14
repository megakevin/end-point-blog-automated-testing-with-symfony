<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testIndexWorks()
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/');

        // Assert
        $this->assertResponseStatusCodeSame(200);
    }

    public function testIndexShowsTheCorrectHeadingAndForm()
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/');

        // Assert
        $this->assertSelectorTextContains('div.wrapper h1', 'Incredible Weather App');
        $this->assertSelectorExists('form[method="POST"]');
        $this->assertSelectorExists('form[method="POST"] input[name="city"]');
        $this->assertSelectorExists('form[method="POST"] input[name="state"]');
        $this->assertSelectorExists('form[method="POST"] input[type="submit"][value="Get Weather"]');
    }

    public function testSubmittingTheSearchFormRedirectsToTheShowRouteWithTheSubmittedParameters()
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/');

        $client->submitForm('Get Weather', [
            'city' => 'New York',
            'state' => 'NY'
        ]);

        // Assert
        $this->assertResponseRedirects('/show/New%20York/NY');
    }

    public function testShowDisplaysAValidationErrorIfGivenAnInvalidState()
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/show/New%20York/NOT%20A%20STATE');

        // Assert
        $this->assertSelectorTextContains('ul li', 'Please enter a valid US state.');
    }

    public function testShowDisplaysANotFoundErrorIfGivenAnInvalidCity()
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/show/not_a_city/NY');

        // Assert
        $this->assertSelectorTextContains('ul li', 'Sorry, we could not find that city.');
    }

    public function testShowDisplaysAllWeatherInfoIfGivenValidInput()
    {
        // Arrange
        $client = static::createClient();

        // Act
        $client->request('GET', '/show/New%20York/NY');

        // Assert
        $this->assertSelectorTextContains('div.wrapper', 'The current weather');
        $this->assertSelectorTextContains('div.wrapper', 'Temp');
        $this->assertSelectorTextContains('div.wrapper', 'Feels like');
        $this->assertSelectorTextContains('div.wrapper', 'Min');
        $this->assertSelectorTextContains('div.wrapper', 'Max');
        $this->assertSelectorTextContains('div.wrapper', 'Pressure');
        $this->assertSelectorTextContains('div.wrapper', 'Humidity');
        $this->assertSelectorTextContains('div.wrapper', 'Visibility');
        $this->assertSelectorTextContains('div.wrapper', 'Wind');
        $this->assertSelectorTextContains('div.wrapper', 'Speed');
        $this->assertSelectorTextContains('div.wrapper', 'Degree');
        $this->assertSelectorTextContains('div.wrapper', 'Gust');
    }


}
