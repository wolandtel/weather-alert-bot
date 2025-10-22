<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class Location
{
    public function __construct(
        private string $latitude,
        private string $longitude,
        private string $name = '',
        private string $timezone = '',
        private string $id = '',
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

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
