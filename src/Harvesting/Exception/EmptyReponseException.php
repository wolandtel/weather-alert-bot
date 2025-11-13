<?php

declare(strict_types=1);

namespace App\Harvesting\Exception;

use RuntimeException;
use Throwable;

final class EmptyReponseException extends RuntimeException
{
    public const string EMPTY_RESPONSE_MESSAGE = 'Empty response';
    public const int EMPTY_RESPONSE_CODE = -20;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(self::EMPTY_RESPONSE_MESSAGE, self::EMPTY_RESPONSE_CODE, $previous);
    }
}
