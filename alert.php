<?php

use Classes\Alterter\ThresholdAlerter;
use Classes\Dto\Location;
use Classes\Formatter\RuLocaleFormatter;
use Classes\Harvester\YandexHarvester;
use Classes\HttpClient\CurlHttpClient;
use Classes\Richtext\MarkdownRichtext;
use Classes\Sender\TelegramSender;

spl_autoload_register(static function (string $class) {
    require_once strtr($class, ['\\' => '/']) . '.php';
});

$config = require 'Config.php';

(new ThresholdAlerter(
    (new YandexHarvester(new CurlHttpClient()))
        ->setLocation(new Location(
            $config->latitude,
            $config->longitude,
            $config->location,
        )),
    new MarkdownRichtext(),
    new RuLocaleFormatter(),
    (new TelegramSender(new CurlHttpClient()))
        ->setApiKey($config->tgApiKey)
        ->setChatId($config->tgChatId),
))->setThreshold($config->threshold)
    ->alert();
