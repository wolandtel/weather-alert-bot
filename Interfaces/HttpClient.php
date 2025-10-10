<?php

declare(strict_types=1);

namespace Interfaces;

interface HttpClient
{
    public function setHeaders(array $headers): self;
    public function get(string $url): string;
    public function post(string $url, string $data): string;
}
