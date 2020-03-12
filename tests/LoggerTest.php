<?php

namespace Hedii\LaravelGelfLogger\Tests;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Orchestra\Testbench\TestCase as Orchestra;
use Tumichnix\TelegramLogger\Factory;

class LoggerTest extends Orchestra
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('logging.default', 'telegram');
        $app['config']->set('logging.channels.telegram', [
            'driver' => 'custom',
            'via' => Factory::class,
            'level' => 'debug',
        ]);
    }

    /** @test */
    public function it_should_be_monolog(): void
    {
        $logger = Log::channel('telegram');

        $this->assertInstanceOf(Logger::class, $logger->getLogger());
    }

    /** @test */
    public function it_should_have_a_handler(): void
    {
        $logger = Log::channel('telegram');

        $this->assertCount(1, $logger->getHandlers());
    }
}
