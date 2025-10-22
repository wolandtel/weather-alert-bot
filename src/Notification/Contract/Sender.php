<?php

declare(strict_types=1);

namespace App\Notification\Contract;

interface Sender
{
    public function send(string $message, string $messageFormat = ''): void;
}
