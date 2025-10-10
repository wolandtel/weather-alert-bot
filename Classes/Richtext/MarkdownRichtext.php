<?php

declare(strict_types=1);

namespace Classes\Richtext;

use Interfaces\Richtext;

final class MarkdownRichtext implements Richtext
{
    public function getMode(): string
    {
        return 'markdown';
    }

    public function getLink(string $text, string $url): string
    {
        return "[$text]($url)";
    }

    public function getLineFeed(): string
    {
        return "\n";
    }
}
