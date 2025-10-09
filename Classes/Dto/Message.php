<?php

declare(strict_types=1);

namespace Classes\Dto;

final readonly class Message
{
    public function __construct(
        private string $text,
        private Day $day,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getDay(): Day
    {
        return $this->day;
        
    }
}
