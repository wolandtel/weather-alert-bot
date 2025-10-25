<?php

declare(strict_types=1);

namespace App\Logging\Contract;

interface Logger
{
    public function error(string $message): void;
}
