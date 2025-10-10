<?php

declare(strict_types=1);

namespace Classes\Dto;

use DateTimeImmutable;

final class Day
{
    public function __construct(
        private ?DateTimeImmutable $date = null,
        private ?float $temperature = null,
        private string $link  = '',
        private string $dayOfWeek = '',
    ) {
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): ?DateTimeImmutable
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

    public function setDayOfWeek(string $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }
}
