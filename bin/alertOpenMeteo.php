<?php

declare(strict_types=1);

use App\Alerting\Contract\Alerter;
use App\Configuration\Contract\Config;
use DI\Container;

/** @var Container $container */
$container = null;

require_once __DIR__ . '/../src/bootstrap.php';

/** @var Config $config */
$config = $container->get(Config::class);
/** @var Alerter $alerter */
$alerter = $container->get(Alerter::class);

$alerter->setLocation($config->getLocation())
    ->setThreshold($config->getThreshold())
    ->alert();
