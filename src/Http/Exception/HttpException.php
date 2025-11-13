<?php

declare(strict_types=1);

namespace App\Http\Exception;

use RuntimeException;
use Throwable;

final class HttpException extends RunTimeException
{
    private int $httpCode;
    private string $url;

    public function __construct(int $httpCode, string $url = '', string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpCode = $httpCode;
        $this->url = $url;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
