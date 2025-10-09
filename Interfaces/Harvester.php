<?php

declare(strict_types=1);

namespace Interfaces;

use Classes\Dto\Day;
use Classes\Dto\Location;

interface Harvester
{
    public function setLocation(Location $location): self;
    /** @return Day[] */
    public function getTemperatureData(): array;
}
