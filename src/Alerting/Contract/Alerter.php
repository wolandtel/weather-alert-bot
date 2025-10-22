<?php

declare(strict_types=1);

namespace App\Alerting\Contract;

use App\Dto\Location;

interface Alerter
{
    public function alert(): self;
    public function setThreshold(float $threshold): self;
    public function setLocation(Location $location): self;
}
