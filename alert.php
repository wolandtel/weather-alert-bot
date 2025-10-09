<?php

use Classes\Alterter\ThresholdAlerter;
use Classes\Dto\Location;
use Classes\Formatter\RuFormatter;
use Classes\Harvester\YandexHarvester;
use Classes\Retriever\CurlRetriever;
use Classes\Sender\TelegramSender;

spl_autoload_register(static function (string $class) {
    require_once strtr($class, ['\\' => '/']) . '.php';
});

$config = require 'Config.php';

(new TelegramSender(
    new CurlRetriever(),
    (new ThresholdAlerter(
        (new YandexHarvester(
            new CurlRetriever(),
        ))->setLocation(new Location(
            $config->latitude,
            $config->longitude,
            $config->location,
        ))
    ))->setThreshold($config->threshold),
    new RuFormatter(),
))->setApiKey($config->tgApiKey)
    ->setChatId($config->tgChatId)
    ->send();
