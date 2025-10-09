<?php

declare(strict_types=1);

namespace Classes\Alterter;

use Classes\Dto\Message;
use Interfaces\Alerter;
use Interfaces\Harvester;

final class ThresholdAlerter implements Alerter
{
    private float $threshold = 1;

    public function __construct(private readonly Harvester $harvester)
    {
    }

    public function setThreshold(float $threshold): self
    {
        $this->threshold = $threshold;
        return $this;
    }

    /** @return Message[] */
    public function getMessages(): array
    {
        $messages = [];
        foreach ($this->harvester->getTemperatureData() as $day) {
            if ($day->getTemperature() < $this->threshold) {
                $messages[] = new Message(
                    'Минимальная температура воздуха на [%s](%s): %s°C.',
                    $day,
                );
            }
        }

        return $messages;
    }
}
