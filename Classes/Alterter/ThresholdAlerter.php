<?php

declare(strict_types=1);

namespace Classes\Alterter;

use Interfaces\Alerter;
use Interfaces\Harvester;
use Interfaces\LocaleFormatter;
use Interfaces\Richtext;
use Interfaces\Sender;

final class ThresholdAlerter implements Alerter
{
    private float $threshold = 1;

    public function __construct(
        private readonly Harvester $harvester,
        private readonly Richtext $richtext,
        private readonly LocaleFormatter $localeFormatter,
        private readonly Sender $sender,
    ) {
    }

    public function setThreshold(float $threshold): self
    {
        $this->threshold = $threshold;
        return $this;
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
