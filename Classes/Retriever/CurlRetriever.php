<?php

namespace Classes\Retriever;

use Interfaces\Retriever;

class CurlRetriever implements Retriever
{
    private const string USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
        . ' AppleWebKit/537.36 (KHTML, like Gecko)'
        . ' Chrome/127.0 Safari/537.36';
    private const array OPTIONS = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => self::USER_AGENT,
        CURLOPT_ENCODING => '', // поддержка gzip/deflate
    ];

    private array $options = self::OPTIONS;

    public function get(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $this->options);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    public function setOptions(array $options): self
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
        return $this;
    }
}
