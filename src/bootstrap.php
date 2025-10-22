<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Throwable $e) {
    fwrite(STDERR, "Не удалось загрузить .env файл: {$e->getMessage()}" . PHP_EOL);
    exit(1);
}

try {
    $containerBuilder = new ContainerBuilder();
    if ($_ENV['APP_ENV'] === 'prod') {
        $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
        $containerBuilder->writeProxiesToFile(true, __DIR__ . '/../var/cache/proxies');
    }

    $containerBuilder->useAttributes(true);
    $containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');

    return $containerBuilder->build();
} catch (Throwable $e) {
    fwrite(STDERR, '[' . date('Y-m-d H:i:s') . '] Произошла ошибка: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Trace: ' . $e->getTraceAsString() . PHP_EOL);

    exit(1);
}
