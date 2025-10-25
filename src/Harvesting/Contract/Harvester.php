<?php

declare(strict_types=1);

namespace App\Harvesting\Contract;

use App\Dto\Day;

interface Harvester
{
    /** @return Day[] */
    public function getTemperatureData(): array;
}
