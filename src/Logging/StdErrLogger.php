<?php

declare(strict_types=1);

namespace App\Logging;

use App\Logging\Contract\Logger;
use Throwable;

final class StdErrLogger implements Logger
{
    public function exception(Throwable $exception): void
    {
        $this->error($exception->getMessage());
        fwrite(STDERR, 'Trace: ' . $exception->getTraceAsString() . PHP_EOL);
    }

    public function error(string $message): void
    {
        fwrite(STDERR, '[' . date('Y-m-d H:i:s') . '] Произошла ошибка: ' . $message . PHP_EOL);
    }
}
