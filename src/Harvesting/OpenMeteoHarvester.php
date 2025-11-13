<?php

declare(strict_types=1);

namespace App\Harvesting;

use App\Configuration\Contract\Config;
use App\Dto\Day;
use App\Dto\Temperature;
use App\Harvesting\Exception\EmptyReponseException;
use App\Http\Contract\HttpClient;
use App\Http\Exception\HttpException;
use App\Logging\Contract\Logger;
use DateMalformedStringException;
use DateTimeImmutable;
use JsonException;

final class OpenMeteoHarvester extends AbstractHarvester
{
    private const string BASE_URL = 'https://api.open-meteo.com';
    private const string URL = self::BASE_URL . '/v1/forecast?latitude=%s&longitude=%s&timezone=%s'
        . '&daily=temperature_2m_max,temperature_2m_min';
    private const string ACCUWEATHER_LINK = 'http://www.accuweather.com/ru/ru/'
        . '%s/%s/daily-weather-forecast/%s?unit=c&day=%d';

    public function __construct(
        Config $config,
        Logger $logger,
        private readonly HttpClient $httpClient,
    ) {
        parent::__construct($config, $logger);
    }

    /**
     * @return Day[]
     *
     * @throws HttpException
     * @throws EmptyReponseException
     */
    protected function harvestTemperatureData(): array
    {
        $response = '';
        try {
            $response = $this->httpClient->get(sprintf(
                self::URL,
                $this->location->getLatitude(),
                $this->location->getLongitude(),
                urlencode($this->location->getTimezone()),
            ));

            if (empty($response)) {
                throw new EmptyReponseException();
            }

            $rawData = json_decode(
                $response,
                false,
                5,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            $this->logger->error("Got string: '$response' [URL: {$this->httpClient->getLastEffectiveUrl()}]");
            $this->logger->exception($e);

            return [];
        }

        $days = [];
        foreach ($rawData->daily->time as $index => $day) {
            try {
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
            } catch (DateMalformedStringException $e) {
                $this->logger->exception($e);
            }
        }

        return $days;
    }
}
