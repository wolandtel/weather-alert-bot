<?php

declare(strict_types=1);

namespace Classes\Sender;

use Interfaces\HttpClient;
use Interfaces\Sender;
use JsonException;

final class TelegramSender implements Sender
{
    private const string API_ENDPOINT = 'https://api.telegram.org/bot%s/sendMessage';

    private ?string $apiKey = null;
    private ?int $chatId = null;

    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    public function setChatId(int $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /** @throws JsonException */
    public function send(string $message, string $messageFormat = ''): void
    {
        $this->processResponse(
            $this->httpClient->setHeaders(['Content-Type: application/json'])
                ->post(
                    sprintf(self::API_ENDPOINT, $this->apiKey),
                    json_encode(
                        [
                            'chat_id' => $this->chatId,
                            'text' => $message,
                            'parse_mode' => $messageFormat,
                            'link_preview_options' => [
                                'is_disabled' => true,
                            ],
                        ],
                        JSON_THROW_ON_ERROR
                    ),
                )
        );
    }

    /** @throws JsonException */
    private function processResponse(string $response): void
    {
        $tgResponse = json_decode($response, false, 5, JSON_THROW_ON_ERROR);
        if (empty($tgResponse->ok)) {
            fwrite(
                STDERR,
                json_encode($tgResponse, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    . "\n"
            );
        }
    }
}
