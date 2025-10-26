#! /usr/bin/env php
<?php

declare(strict_types=1);

use App\Alerting\Contract\Alerter;
use App\Alerting\DailyAverageTemperatureAlerter;
use App\Alerting\MinTemperatureAlerter;
use App\Harvesting\Contract\Harvester;
use App\Harvesting\OpenMeteoHarvester;
use App\Logging\Contract\Logger;
use DI\Container;

use function DI\autowire;

/** @var Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

try {
    $container->set(Harvester::class, autowire(OpenMeteoHarvester::class));

    foreach ([MinTemperatureAlerter::class, DailyAverageTemperatureAlerter::class] as $alerterClass) {
        /** @var Alerter $alerter */
        $alerter = $container->get($alerterClass);
        $alerter->alert();
    }
} catch (Throwable $e) {
    $logger = $container->get(Logger::class);
    $logger->exception($e);

    exit(1);
}
