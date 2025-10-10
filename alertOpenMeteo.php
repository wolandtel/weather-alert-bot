<?php

use Classes\Alterter\ThresholdAlerter;
use Classes\Dto\Location;
use Classes\Formatter\RuLocaleFormatter;
use Classes\Harvester\OpenMeteoHarvester;
use Classes\HttpClient\CurlHttpClient;
use Classes\Richtext\MarkdownRichtext;
use Classes\Sender\TelegramSender;

spl_autoload_register(static function (string $class) {
    require_once strtr($class, ['\\' => '/']) . '.php';
});

/** @var Config $config */
$config = require 'Config.php';

(new ThresholdAlerter(
    (new OpenMeteoHarvester(new CurlHttpClient()))
        ->setLocation(
            (new Location(
                $config->latitude,
                $config->longitude,
                $config->locationName,
            ))
                ->setId($config->locationId)
                ->setTimezone($config->timezone),
        ),
    new MarkdownRichtext(),
    new RuLocaleFormatter(),
    (new TelegramSender(new CurlHttpClient()))
        ->setApiKey($config->tgApiKey)
        ->setChatId($config->tgChatId),
))->setThreshold($config->threshold)
    ->alert();
