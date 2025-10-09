<?php

declare(strict_types=1);

namespace Classes\Sender;

use Interfaces\Alerter;
use Interfaces\Formatter;
use Interfaces\Retriever;
use Interfaces\Sender;
use JsonException;

final class TelegramSender implements Sender
{
    private const string API_ENDPOINT = 'https://api.telegram.org/bot%s/sendMessage';

    private ?string $apiKey = null;
    private ?int $chatId = null;

    public function __construct(
        private readonly Retriever $retriever,
        private readonly Alerter $alerter,
        private readonly Formatter $formatter,
    ) {
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
    public function send(): void
    {
        $message = $this->prepareMessage();
        if (empty($message)) {
            return;
        }

        $this->retriever->setOptions([
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode(
                [
                    'chat_id' => $this->chatId,
                    'text' => $message,
                    'parse_mode' => 'markdown',
                    'link_preview_options' => [
                        'is_disabled' => true,
                    ],
                ],
            JSON_THROW_ON_ERROR
            ),
        ]);

        $this->processResponse(
            $this->retriever->get(sprintf(self::API_ENDPOINT, $this->apiKey))
        );
    }

    private function prepareMessage(): string
    {
        $messages = [];
        foreach ($this->alerter->getMessages() as $message) {
            if (!empty($message->getDay()->getLink())) {
                $messages[] = sprintf(
                    $message->getText(),
                    $message->getDay()->getDate(),
                    $message->getDay()->getLink(),
                    $this->formatter->number($message->getDay()->getTemperature()),
                );
            }
        }

        return implode("\n", $messages);
    }

    /** @throws JsonException */
    private function processResponse(string $response): void
    {
        $tgResponse = json_decode($response, false, 5, JSON_THROW_ON_ERROR);
        if (empty($tgResponse->ok)) {
            echo json_encode($tgResponse, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                . "\n";
        }
    }
}
