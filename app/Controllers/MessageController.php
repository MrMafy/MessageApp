<?php

namespace App\Controllers;

use App\Exceptions\DatabaseException;
use App\Modules\DatabaseConnection;
use App\Models\MessageModel;
use PDOException;
use PDO;

/**
 * Контроллер для обработки отправки и получения сообщений
 */
class MessageController
{
    private MessageModel $messageModel;

    public function __construct(MessageModel $messageModel)
    {
        $this->messageModel = $messageModel;
    }

    /**
     * @throws DatabaseException
     */
    public function send(string $username, int $categoryId, string $messageText, string $createdAt): void
    {
        try {
            $createdAtFormatted = date('Y-m-d H:i:s', strtotime($createdAt));

            $redis = DatabaseConnection::getRedisClient();

            $messageQueue = new \App\Modules\MessageQueue($redis);
            $messageQueue->sendToQueue($categoryId, $username, $messageText, $createdAtFormatted);
    
            echo 'Сообщение успешно отправлено в очередь Redis!';
        } catch (PDOException $e) {
            throw new DatabaseException('Ошибка при отправке сообщения в очередь Redis: ' . $e->getMessage());
        }
    }

    /**
     * @throws DatabaseException
     */
    public function get(): void
    {
        try {
            $categoryId = $_GET['categoryId'] ?? null;
            $messages = $this->messageModel->getMessagesByCategory($categoryId);

            header('Content-Type: application/json');
            echo json_encode($messages);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            throw new DatabaseException('Ошибка при получении сообщений: ' . $e->getMessage());
        }
    }
}