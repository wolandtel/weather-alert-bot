<?php

declare(strict_types=1);

namespace Classes\Dto;

final class Location
{
    private string $timezone = '';

    public function __construct(
        private readonly string $latitude,
        private readonly string $longitude,
        private readonly string $name = '',
    ) {
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }
}
