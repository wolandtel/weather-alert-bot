<?php

declare(strict_types=1);

namespace App\Alerting;

use App\Dto\Day;

final class DailyAverageTemperatureAlerter extends BaseAlerter
{
    protected function getMessage(): string
    {
        $messages = [];
        foreach ($this->harvester->getTemperatureData() as $day) {
            $averageTemperature = $this->getAverageTemperature($day);
            if ($averageTemperature < $this->threshold) {
                $date = $this->getDateFormatted($day);
                $messages[] = sprintf(
                    'Среднесуточная температура воздуха на %s: %s°C.',
                    !empty($day->getLink())
                        ? $this->richtext->getLink($date, $day->getLink())
                        : $date,
                    $this->localeFormatter->number($averageTemperature),
                );
            }
        }

        return implode($this->richtext->getLineFeed(), $messages);
    }

    private function getAverageTemperature(Day $day): float
    {
        return round(($day->getTemperature()->getMin() + $day->getTemperature()->getMax()) / 2, 1);
    }
}
