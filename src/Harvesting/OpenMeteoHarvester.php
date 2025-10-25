<?php

declare(strict_types=1);

namespace App\Harvesting;

use App\Configuration\Contract\Config;
use App\Dto\Day;
use App\Dto\Location;
use App\Dto\Temperature;
use App\Harvesting\Contract\Harvester;
use App\Http\Contract\HttpClient;
use App\Logging\Contract\Logger;
use DateTimeImmutable;
use Exception;
use JsonException;

final class OpenMeteoHarvester implements Harvester
{
    private const string BASE_URL = 'https://api.open-meteo.com';
    private const string URL = self::BASE_URL . '/v1/forecast?latitude=%s&longitude=%s&timezone=%s'
        . '&daily=temperature_2m_max,temperature_2m_min';
    private const string ACCUWEATHER_LINK = 'http://www.accuweather.com/ru/ru/'
        . '%s/%s/daily-weather-forecast/%s?unit=c&day=%d';
    private Location $location;

    public function __construct(
        Config $config,
        private readonly HttpClient $httpClient,
        private readonly Logger $logger,
    ) {
        $this->location = $config->getLocation();
    }

    /**
     * @return Day[]
     * @throws Exception
     */
    public function getTemperatureData(): array
    {
        $response = '';
        try {
            $response = $this->httpClient->get(sprintf(
                self::URL,
                $this->location->getLatitude(),
                $this->location->getLongitude(),
                urlencode($this->location->getTimezone()),
            ));
            $rawData = json_decode(
                $response,
                false,
                5,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            $this->logger->exception($e);
            $this->logger->error("Got string: '$response'");

            return [];
        }

        $days = [];
        foreach ($rawData->daily->time as $index => $day) {
            $days[] = new Day(
                new DateTimeImmutable($day),
                new Temperature(
                    (float)$rawData->daily->temperature_2m_min[$index],
                    (float)$rawData->daily->temperature_2m_max[$index],
                ),
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
