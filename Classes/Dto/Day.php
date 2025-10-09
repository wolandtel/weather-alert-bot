<?php

declare(strict_types=1);

namespace Classes\Dto;

final class Day
{
    public function __construct(
        private string $date = '',
        private ?float $temperature = null,
        private string $link  = ''
    ) {
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
        
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
