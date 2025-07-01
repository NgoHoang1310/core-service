<?php
// app/Services/RabbitMQService.php

namespace App\Services\Queue;

use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements QueueService
{
    protected $connection;
    protected $channel;
    protected $exchange;

    public function __construct($connection, $channel, $exchange)
    {
        $this->connection = $connection;
        $this->channel = $channel;
        $this->exchange = $exchange;
    }

    public function setup()
    {
        // Khai báo exchange
        $this->channel->exchange_declare(
            $this->exchange,
            'direct',
            false,
            true,
            false
        );

        // Bạn có thể khai báo các queue mặc định ở đây nếu cần

        return $this;
    }
    public function publish(string $message, string $routingKey, array $properties = [])
    {
        $msg = new AMQPMessage(
            $message,
            array_merge([
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ], $properties)
        );

        $this->channel->basic_publish($msg, $this->exchange, $routingKey);

        return $this;
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
