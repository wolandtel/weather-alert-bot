<?php

declare(strict_types=1);

namespace App\Alerting\Contract;

interface Alerter
{
    public function alert(): self;
}
