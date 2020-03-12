<?php

namespace Tumichnix\TelegramLogger;

use Monolog\Logger;

class Factory
{
    public function __invoke(array $config)
    {
        return new Logger(
            config('app.name'),
            [
                new Handler(
                    $config['token'],
                    $config['chat_id'],
                    $config['level']
                )
            ]
        );
    }
}
