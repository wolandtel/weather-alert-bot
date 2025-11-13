<?php

declare(strict_types=1);

namespace App\Configuration\Contract;

use App\Dto\Location;

interface Config
{
    public function isDev(): bool;
    public function isProd(): bool;
    public function getLocation(): Location;
    public function getThresholdMin(): float;
    public function getThresholdDailyAverage(): float;
    public function getTgApiKey(): string;
    public function getTgChatId(): int;
}
