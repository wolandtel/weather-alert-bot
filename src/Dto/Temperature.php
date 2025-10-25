<?php

declare(strict_types=1);

namespace App\Dto;

final class Temperature
{
    public function __construct(private ?float $min = null, private ?float $max = null)
    {
    }

    public function setMax(float $max): self
    {
        $this->max = $max;
        return $this;
    }

    public function setMin(float $min): self
    {
        $this->min = $min;
        return $this;
    }

    public function getMin(): ?float
    {
        return $this->min;
    }

    public function getMax(): ?float
    {
        return $this->max;
    }
}
