<?php

declare(strict_types=1);

namespace App\Formatting\Contract;

use DateTimeImmutable;

interface LocaleFormatter
{
    public function number(float $number): string;
    public function date(DateTimeImmutable $date): string;
}
