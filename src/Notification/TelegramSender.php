<?php

declare(strict_types=1);

namespace App\Notification;

use App\Configuration\Contract\Config;
use App\Http\Contract\HttpClient;
use App\Logging\Contract\Logger;
use App\Notification\Contract\Sender;
use JsonException;

final class TelegramSender implements Sender
{
    private const string API_ENDPOINT = 'https://api.telegram.org/bot%s/sendMessage';

    private string $apiKey;
    private int $chatId;

    public function __construct(
        Config $config,
        private readonly HttpClient $httpClient,
        private readonly Logger $logger,
    ) {
        $this->apiKey = $config->getTgApiKey();
        $this->chatId = $config->getTgChatId();
    }

    public function send(string $message, string $messageFormat = ''): void
    {
        try {
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
        } catch (JsonException $e) {
            $this->logger->exception($e);
        }
    }

    /** @throws JsonException */
    private function processResponse(string $response): void
    {
        $tgResponse = json_decode($response, false, 5, JSON_THROW_ON_ERROR);
        if (empty($tgResponse->ok)) {
            $this->logger->error(
                json_encode($tgResponse, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            );
        }
    }
}
