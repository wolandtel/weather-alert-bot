<?php

declare(strict_types=1);

namespace Classes\Harvester;

use Classes\Dto\Day;
use Classes\Dto\Location;
use DateMalformedStringException;
use DateTimeImmutable;
use Interfaces\Harvester;
use Interfaces\HttpClient;
use JsonException;

final class OpenMeteoHarvester implements Harvester
{
    private const string BASE_URL = 'https://api.open-meteo.com';
    private const string URL = self::BASE_URL . '/v1/forecast?latitude=%s&longitude=%s&timezone=%s'
        . '&daily=temperature_2m_max,temperature_2m_min';
    private const string ACCUWEATHER_LINK = 'http://www.accuweather.com/ru/ru/'
        . '%s/%s/daily-weather-forecast/%s?unit=c&day=%d';
    private ?Location $location = null;

    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return Day[]
     * @throws JsonException
     * @throws DateMalformedStringException
     */
    public function getTemperatureData(): array
    {
        $rawData = json_decode(
            $this->httpClient->get(sprintf(
                self::URL,
                $this->location->getLatitude(),
                $this->location->getLongitude(),
                urlencode($this->location->getTimezone()),
            )),
            false,
            5,
            JSON_THROW_ON_ERROR
        );

        $days = [];
        foreach ($rawData->daily->time as $index => $day) {
            $days[] = new Day(
                new DateTimeImmutable($day),
                (float)$rawData->daily->temperature_2m_min[$index],
                sprintf(
                    self::ACCUWEATHER_LINK,
                    $this->location->getName(),
                    $this->location->getId(),
                    $this->location->getId(),
                    $index + 1,
                ),
            );
        }

        return $days;
    }
}

