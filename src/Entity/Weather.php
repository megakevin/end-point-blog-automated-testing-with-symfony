<?php

namespace App\Entity;

class Weather
{
    private $city;
    private $state;
    private $description;
    private $summary;
    private $temperature;
    private $feelsLike;
    private $tempMin;
    private $tempMax;
    private $pressure;
    private $humidity;
    private $visibility;
    private $windSpeed;
    private $windDeg;
    private $windGust;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getFeelsLike(): ?float
    {
        return $this->feelsLike;
    }

    public function setFeelsLike(float $feelsLike): self
    {
        $this->feelsLike = $feelsLike;

        return $this;
    }

    public function getTempMin(): ?float
    {
        return $this->tempMin;
    }

    public function setTempMin(float $tempMin): self
    {
        $this->tempMin = $tempMin;

        return $this;
    }

    public function getTempMax(): ?float
    {
        return $this->tempMax;
    }

    public function setTempMax(float $tempMax): self
    {
        $this->tempMax = $tempMax;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(float $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(float $windSpeed): self
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDeg(): ?int
    {
        return $this->windDeg;
    }

    public function setWindDeg(int $windDeg): self
    {
        $this->windDeg = $windDeg;

        return $this;
    }

    public function getWindGust(): ?float
    {
        return $this->windGust;
    }

    public function setWindGust(float $windGust): self
    {
        $this->windGust = $windGust;

        return $this;
    }

    public static function build(WeatherQuery $weatherQuery, array $apiResponse): Weather
    {
        $weather = (new Weather())
            ->setCity($weatherQuery->getCity())
            ->setState($weatherQuery->getState())
            ->setDescription($apiResponse['weather'][0]['main'])
            ->setSummary($apiResponse['weather'][0]['description'])
            ->setTemperature($apiResponse['main']['temp'])
            ->setFeelsLike($apiResponse['main']['feels_like'])
            ->setTempMin($apiResponse['main']['temp_min'])
            ->setTempMax($apiResponse['main']['temp_max'])
            ->setPressure($apiResponse['main']['pressure'])
            ->setHumidity($apiResponse['main']['humidity'])
            ->setVisibility($apiResponse['visibility'])
            ->setWindSpeed($apiResponse['wind']['speed'])
            ->setWindDeg($apiResponse['wind']['deg'] ?? 0)
            ->setWindGust($apiResponse['wind']['gust'] ?? 0)
        ;

        return $weather;
    }
}
