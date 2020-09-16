<?php

namespace App\Tests\Functional\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\WeatherQuery;
use App\Repository\WeatherQueryRepository;

class WeatherQueryRepositoryTest extends KernelTestCase
{
    public function testAddSavesANewRecordIntoTheDatabase()
    {
        // Arrange
        $testWeatherQuery1 = WeatherQuery::build('My City 1', 'MY STATE 1');
        $testWeatherQuery2 = WeatherQuery::build('My City 2', 'MY STATE 2');

        self::bootKernel();
        $repository = self::$container->get(WeatherQueryRepository::class);

        // Act
        $repository->add($testWeatherQuery1);
        $repository->add($testWeatherQuery2);

        // Assert
        $records = $repository->findAll();

        $this->assertEquals(2, count($records));
        $this->assertEquals('My City 1', $records[0]->getCity());
        $this->assertEquals('MY STATE 1', $records[0]->getState());
        $this->assertEquals('My City 2', $records[1]->getCity());
        $this->assertEquals('MY STATE 2', $records[1]->getState());
    }
}
