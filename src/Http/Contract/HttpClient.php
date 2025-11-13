<?php

declare(strict_types=1);

namespace App\Http\Contract;

use App\Http\Exceptions\HttpException;

interface HttpClient
{
    public function setHeaders(array $headers): self;
    /** @throws HttpException */
    public function get(string $url): string;
    /** @throws HttpException */
    public function post(string $url, string $data): string;
    public function getLastEffectiveUrl(): string;
}
