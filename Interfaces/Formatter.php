<?php

declare(strict_types=1);

namespace Interfaces;

interface Formatter
{
    public function number(float $number): string;
}
