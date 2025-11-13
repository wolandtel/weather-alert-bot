<?php

declare(strict_types=1);

namespace App\Alerting;

use App\Configuration\Contract\Config;

final class MinTemperatureAlerter extends BaseAlerter
{
    protected function setThreshold(Config $config): void
    {
        $this->threshold = $config->getThresholdMin();
    }

    protected function getTitle(): string
    {
        return 'Минимальная температура воздуха';
    }

    protected function getMessage(): string
    {
        $messages = [];
        foreach ($this->harvester->getTemperatureData() as $day) {
            if ($day->getTemperature()->getMin() < $this->threshold) {
                $date = $this->getDateFormatted($day);
                $messages[] = sprintf(
                    '%s: %s°C.',
                    !empty($day->getLink())
                        ? $this->richtext->getLink($date, $day->getLink())
                        : $date,
                    $this->localeFormatter->number($day->getTemperature()->getMin()),
                );
            }
        }

        return implode($this->richtext->getLineFeed(), $messages);
    }
}
