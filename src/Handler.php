<?php

namespace Tumichnix\TelegramLogger;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Monolog\DateTimeImmutable;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class Handler extends AbstractProcessingHandler
{
    private ?string $token;
    private ?int $chatId;

    public function __construct($token, $chatId, $level = Logger::DEBUG, bool $bubble = true)
    {
        $this->token = $token;
        $this->chatId = $chatId;

        $level = Logger::toMonologLevel($level);

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        if (is_null($this->token) || is_null($this->chatId)) {
            return;
        }

        $url = sprintf(
            'https://api.telegram.org/bot%s/sendMessage',
            $this->token
        );

        try {
            Http::asJson()
                ->get($url, [
                    'text' => $this->format($record['message'], $record['level_name'], $record['datetime']),
                    'chat_id' => $this->chatId,
                    'parse_mode' => 'html',
                ])
                ->throw();
        } catch (RequestException $exception) {
            //
        }
    }

    protected function format(string $text, string $level, DateTimeImmutable $date): string
    {
        return sprintf(
            '<b>%s</b> (%s - %s - %s)%s%s',
            config('app.name'),
            config('app.env'),
            $level,
            $date->jsonSerialize(),
            PHP_EOL,
            $text);
    }
}
