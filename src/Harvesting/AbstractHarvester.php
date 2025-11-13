<?php

declare(strict_types=1);

namespace App\Harvesting;

use App\Configuration\Contract\Config;
use App\Dto\Day;
use App\Dto\Location;
use App\Harvesting\Contract\Harvester;
use App\Harvesting\Exception\EmptyReponseException;
use App\Http\Exception\HttpException;
use App\Logging\Contract\Logger;
use RuntimeException;

abstract class AbstractHarvester implements Harvester
{
    protected Location $location;

    /** @var Day[] */
    private ?array $temperatureData = null;

    public function __construct(
        Config $config,
        protected readonly Logger $logger,
    ) {
        $this->location = $config->getLocation();
    }

    /**
     * @return Day[]
     *
     * @throws HttpException
     * @throws EmptyReponseException
     */
    abstract protected function harvestTemperatureData(): array;

    public function getTemperatureData(): array
    {
        if ($this->temperatureData === null) {
            try {
                $this->temperatureData = $this->harvestTemperatureData();
            } catch (RuntimeException $e) {
                $this->temperatureData = [];
                $this->logger->exception($e);
            }
        }

        return $this->temperatureData;
    }
}
