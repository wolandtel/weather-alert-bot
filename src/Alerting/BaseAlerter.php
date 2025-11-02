<?php

declare(strict_types=1);

namespace App\Alerting;

use App\Alerting\Contract\Alerter;
use App\Configuration\Contract\Config;
use App\Dto\Day;
use App\Formatting\Contract\LocaleFormatter;
use App\Formatting\Contract\Richtext;
use App\Harvesting\Contract\Harvester;
use App\Notification\Contract\Sender;

abstract class BaseAlerter implements Alerter
{
    protected float $threshold;

    public function __construct(
        Config $config,
        protected readonly Harvester $harvester,
        protected readonly Richtext $richtext,
        protected readonly LocaleFormatter $localeFormatter,
        private readonly Sender $sender,
    ) {
        $this->threshold = $config->getThreshold(static::class);
    }

    abstract protected function getMessage(): string;

    protected function getDateFormatted(Day $day): string
    {
        $date = $this->localeFormatter->date($day->getDate());
        if (!empty($day->getDayOfWeek())) {
            $date = "{$day->getDayOfWeek()}, $date";
        }

        return $date;
    }

    final public function alert(): self
    {
        $message = $this->getMessage();
        if (!empty($message)) {
            $this->sender->send($message, $this->richtext->getMode());
        }

        return $this;
    }
}
