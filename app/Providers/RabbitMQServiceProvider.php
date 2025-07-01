<?php

namespace App\Providers;

use App\Services\Queue\Producers\SocketProducer;
use App\Services\Queue\QueueService;
use App\Services\Queue\RabbitMQService;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(QueueService::class, function ($app) {
            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host'),
                config('queue.connections.rabbitmq.hosts.0.port'),
                config('queue.connections.rabbitmq.hosts.0.user'),
                config('queue.connections.rabbitmq.hosts.0.password'),
                config('queue.connections.rabbitmq.hosts.0.vhost')
            );

            $channel = $connection->channel();
            $exchange = config('queue.connections.rabbitmq.options.exchange.name');
            return new RabbitMQService($connection, $channel, $exchange);
        });

        $this->app->singleton(SocketProducer::class, function ($app) {
            return new SocketProducer($app->make(QueueService::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->make(QueueService::class)->setup();
    }
}
