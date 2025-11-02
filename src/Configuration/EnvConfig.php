<?php

declare(strict_types=1);

namespace App\Configuration;

use App\Alerting\DailyAverageTemperatureAlerter;
use App\Alerting\MinTemperatureAlerter;
use App\Configuration\Contract\Config;
use App\Dto\Location;
use App\Logging\Contract\Logger;

final class EnvConfig implements Config
{
    private const array THRESHOLDS = [
        MinTemperatureAlerter::class => 'THRESHOLD_MIN',
        DailyAverageTemperatureAlerter::class => 'THRESHOLD_DAILY_AVERAGE',
    ];

    private Logger $logger;
    private string $environment;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->environment = $_ENV['APP_ENV'] ?: 'dev';
    }

    public function isDev(): bool
    {
        return $this->environment === 'dev';
    }

    public function isProd(): bool
    {
        return $this->environment === 'prod';
    }

    public function getLocation(): Location
    {
        return new Location(
            (string)$_ENV['LATITUDE'],
            (string)$_ENV['LONGITUDE'],
            (string)$_ENV['LOCATION_NAME'],
            (string)$_ENV['TIMEZONE'],
            (string)$_ENV['ACCUWEATHER_LOCATION_ID'],
        );
    }

    public function getThreshold(string $alerterClass): float
    {
        if (!isset(self::THRESHOLDS[$alerterClass])) {
            $this->logger->error("Threshold name for alerter [$alerterClass] is not set.");
        }
        if (!isset($_ENV[self::THRESHOLDS[$alerterClass]])) {
            $this->logger->error("Threshold value for alerter [$alerterClass] is not set.");
        }
        return (float)$_ENV[self::THRESHOLDS[$alerterClass]];
    }

    public function getTgApiKey(): string
    {
        return (string)$_ENV['TG_API_KEY'];
    }

    public function getTgChatId(): int
    {
        return (int)$_ENV['TG_CHAT_ID'];
    }
}
