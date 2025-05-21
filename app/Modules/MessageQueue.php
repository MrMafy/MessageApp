<?php

namespace App\Modules;

use Predis\Client;

/**
 * Класс для работы с очередью сообщений в Redis.
 */
class MessageQueue
{
    private Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function sendToQueue(int $categoryId, string $username, string $messageText, string $createdAt): void
    {
        $messageData = json_encode([
            'categoryId' => $categoryId,
            'username' => $username,
            'messageText' => $messageText,
            'createdAt' => $createdAt,
        ]);

        $this->redis->lpush('message_queue', (array)$messageData);
    }
}