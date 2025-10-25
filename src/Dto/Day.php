<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;

final class Day
{
    private Temperature $temperature;

    public function __construct(
        private ?DateTimeImmutable $date = null,
        ?Temperature $temperature = null,
        private string $link  = '',
        private string $dayOfWeek = '',
    ) {
        if ($temperature === null) {
            $this->temperature = new Temperature();
        } else {
            $this->temperature = $temperature;
        }
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

    public function getTemperature(): Temperature
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
