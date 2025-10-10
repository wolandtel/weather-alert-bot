<?php

declare(strict_types=1);

namespace Interfaces;

use DateTimeImmutable;

interface LocaleFormatter
{
    public function number(float $number): string;
    public function date(DateTimeImmutable $date): string;
}
