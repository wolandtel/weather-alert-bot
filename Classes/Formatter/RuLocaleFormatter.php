<?php

declare(strict_types=1);

namespace Classes\Formatter;

use Interfaces\LocaleFormatter;

final class RuLocaleFormatter implements LocaleFormatter
{
    public function number(float $number): string
    {
        return strtr((string)$number, ['.' => ',']);
    }
}
