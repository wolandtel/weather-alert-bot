<?php

declare(strict_types=1);

namespace App\Formatting\Contract;

interface Richtext
{
    public function getMode(): string;
    public function getLink(string $text, string $url): string;
    public function getLineFeed(): string;
}
