<?php

declare(strict_types=1);

namespace App\Alerting;

use App\Alerting\Contract\Alerter;
use App\Configuration\Contract\Config;
use App\Formatting\Contract\LocaleFormatter;
use App\Formatting\Contract\Richtext;
use App\Harvesting\Contract\Harvester;
use App\Notification\Contract\Sender;

final class MinTemperatureAlerter implements Alerter
{
    private float $threshold;

    public function __construct(
        Config $config,
        private readonly Harvester $harvester,
        private readonly Richtext $richtext,
        private readonly LocaleFormatter $localeFormatter,
        private readonly Sender $sender,
    ) {
        $this->threshold = $config->getThresholdMin();
    }

    public function alert(): self
    {
        $message = $this->getMessage();
        if (!empty($message)) {
            $this->sender->send($message, $this->richtext->getMode());
        }

        return $this;
    }

    private function getMessage(): string
    {
        $messages = [];
        foreach ($this->harvester->getTemperatureData() as $day) {
            $date = $this->localeFormatter->date($day->getDate());
            if (!empty($day->getDayOfWeek())) {
                $date = "{$day->getDayOfWeek()}, $date";
            }

            if ($day->getTemperature() < $this->threshold) {
                $messages[] = sprintf(
                    'Минимальная температура воздуха на %s: %s°C.',
                    !empty($day->getLink())
                        ? $this->richtext->getLink($date, $day->getLink())
                        : $date,
                    $this->localeFormatter->number($day->getTemperature()),
                );
            }
        }

        return implode($this->richtext->getLineFeed(), $messages);
    }
}
