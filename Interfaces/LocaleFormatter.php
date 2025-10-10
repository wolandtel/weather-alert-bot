<?php

declare(strict_types=1);

namespace Interfaces;

interface LocaleFormatter
{
    public function number(float $number): string;
}
