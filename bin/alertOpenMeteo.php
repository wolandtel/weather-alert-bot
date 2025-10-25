#! /usr/bin/env php
<?php

declare(strict_types=1);

use App\Alerting\Contract\Alerter;
use App\Alerting\MinTemperatureAlerter;
use App\Configuration\Contract\Config;
use App\Harvesting\Contract\Harvester;
use App\Harvesting\OpenMeteoHarvester;

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
} catch (Throwable $e) {
    fwrite(STDERR, '[' . date('Y-m-d H:i:s') . '] Произошла ошибка: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Trace: ' . $e->getTraceAsString() . PHP_EOL);

    exit(1);
}
