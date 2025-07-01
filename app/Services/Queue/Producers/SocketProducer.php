<?php
// app/Services/Queue/Producers/VideoProducer.php

namespace App\Services\Queue\Producers;

use App\Models\Notification;

class SocketProducer extends BaseProducer
{
    protected string $routingKey = 'socket.notification';

    public function processSocket(string $room, array $socketData)
    {
        return $this->publish([
            'room' => $room,
            'data' => $socketData,
            'timestamp' => now()->timestamp
        ]);
    }
}
