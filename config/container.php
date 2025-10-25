<?php

declare(strict_types=1);

use App\Alerting\Contract\Alerter;
use App\Alerting\MinTemperatureAlerter;
use App\Configuration\Contract\Config;
use App\Configuration\EnvConfig;
use App\Formatting\Contract\LocaleFormatter;
use App\Formatting\Contract\Richtext;
use App\Formatting\MarkdownRichtext;
use App\Formatting\RuLocaleFormatter;
use App\Harvesting\Contract\Harvester;
use App\Harvesting\YandexHarvester;
use App\Http\Contract\HttpClient;
use App\Http\CurlHttpClient;
use App\Notification\Contract\Sender;
use App\Notification\TelegramSender;

use function DI\autowire;

return [
    Config::class => autowire(EnvConfig::class),
    Alerter::class => autowire(MinTemperatureAlerter::class),
    Harvester::class => autowire(YandexHarvester::class),
    Sender::class => autowire(TelegramSender::class),
    HttpClient::class => autowire(CurlHttpClient::class),
    LocaleFormatter::class => autowire(RuLocaleFormatter::class),
    Richtext::class => autowire(MarkdownRichtext::class),
];
