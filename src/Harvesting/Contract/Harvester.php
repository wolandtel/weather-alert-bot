<?php

declare(strict_types=1);

namespace App\Harvesting\Contract;

use App\Dto\Day;
use App\Dto\Location;

interface Harvester
{
    public function setLocation(Location $location): self;
    /** @return Day[] */
    public function getTemperatureData(): array;
}
