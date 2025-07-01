<?php

// app/Services/Queue/QueueService.php

namespace App\Services\Queue;

interface QueueService
{
    public function publish(string $message, string $routingKey, array $properties = []);

    public function setup();
}
