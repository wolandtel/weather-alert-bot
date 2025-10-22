#! /usr/bin/env php
<?php

declare(strict_types=1);

use App\Alerting\Contract\Alerter;
use App\Configuration\Contract\Config;
use DI\Container;

/** @var Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

try {
    /** @var Config $config */
    $config = $container->get(Config::class);
    /** @var Alerter $alerter */
    $alerter = $container->get(Alerter::class);
    
    $alerter->setLocation($config->getLocation())
        ->setThreshold($config->getThreshold())
        ->alert();
} catch (Throwable $e) {
    fwrite(STDERR, '[' . date('Y-m-d H:i:s') . '] Произошла ошибка: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Trace: ' . $e->getTraceAsString() . PHP_EOL);

    exit(1);
}
