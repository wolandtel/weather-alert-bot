<?php

declare(strict_types=1);

namespace App\Alerting;

use App\Alerting\Contract\Alerter;
use App\Dto\Location;
use App\Formatting\Contract\LocaleFormatter;
use App\Formatting\Contract\Richtext;
use App\Harvesting\Contract\Harvester;
use App\Notification\Contract\Sender;

final class MinTemperatureAlerter implements Alerter
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

    public function setLocation(Location $location): self
    {
        $this->harvester->setLocation($location);
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
