<?php

declare(strict_types=1);

namespace App\Logging\Contract;

use Throwable;

interface Logger
{
    public function error(string $message): void;
    public function exception(Throwable $exception): void;
}
