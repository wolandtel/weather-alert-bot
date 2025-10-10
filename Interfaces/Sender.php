<?php

declare(strict_types=1);

namespace Interfaces;

interface Sender
{
    public function send(string $message, string $messageFormat = ''): void;
}
