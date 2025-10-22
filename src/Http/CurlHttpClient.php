<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\Contract\HttpClient;

final class CurlHttpClient implements HttpClient
{
    private const string DEFAULT_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
        . ' AppleWebKit/537.36 (KHTML, like Gecko)'
        . ' Chrome/127.0 Safari/537.36';
    private const array DEFAULT_OPTIONS = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => self::DEFAULT_USER_AGENT,
        CURLOPT_ENCODING => '', // поддержка gzip/deflate
    ];

    private array $options = self::DEFAULT_OPTIONS;

    public function setHeaders(array $headers): self
    {
        $this->options[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    public function get(string $url): string
    {
        return $this->request($url);
    }

    public function post(string $url, string $data): string
    {
        $this->options[CURLOPT_POST] = true;
        $this->options[CURLOPT_POSTFIELDS] = $data;

        return $this->request($url);
    }

    private function request(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $this->options);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }
}
