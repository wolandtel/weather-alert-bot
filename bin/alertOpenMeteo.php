#! /usr/bin/env php
<?php

declare(strict_types=1);

use App\Alerting\Contract\Alerter;
use App\Alerting\DailyAverageTemperatureAlerter;
use App\Alerting\MinTemperatureAlerter;
use App\Configuration\Contract\Config;
use App\Harvesting\Contract\Harvester;
use App\Harvesting\OpenMeteoHarvester;

use App\Logging\Contract\Logger;

use function DI\autowire;

use DI\Container;

/** @var Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

try {
    $container->set(Harvester::class, autowire(OpenMeteoHarvester::class));
    /** @var Config $config */
    $config = $container->get(Config::class);
    /** @var Alerter $alerter */
    $alerter = $container->get(MinTemperatureAlerter::class);
    $alerter->alert();
    $alerter = $container->get(DailyAverageTemperatureAlerter::class);
    $alerter->alert();
} catch (Throwable $e) {
    $logger = $container->get(Logger::class);
    $logger->exception($e);

    exit(1);
}
