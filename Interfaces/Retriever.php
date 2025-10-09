<?php

declare(strict_types=1);

namespace Interfaces;

interface Retriever
{
    public function get(string $url): string;
    public function setOptions(array $options): self;
}
