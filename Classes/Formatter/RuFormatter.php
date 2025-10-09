<?php

declare(strict_types=1);

namespace Classes\Formatter;

use Interfaces\Formatter;

final class RuFormatter implements Formatter
{
    public function number(float $number): string
    {
        return strtr((string)$number, ['.' => ',']);
    }
}
