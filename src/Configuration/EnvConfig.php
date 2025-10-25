<?php

declare(strict_types=1);

namespace App\Configuration;

use App\Configuration\Contract\Config;
use App\Dto\Location;

final class EnvConfig implements Config
{
    private string $environment;

    public function __construct()
    {
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

    public function getThresholdMin(): float
    {
        return (float)$_ENV['THRESHOLD_MIN'];
    }

    public function getThresholdDailyAverage(): float
    {
        return (float)$_ENV['THRESHOLD_DAILY_AVERAGE'];
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
