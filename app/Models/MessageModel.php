<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Exceptions\DatabaseException;

/**
 * Модель для работы с сообщениями, отвечает за взаимодействие с таблицей `messages` в базе данных
 */
class MessageModel
{
    private PDO $dbConnection;

    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @throws DatabaseException
     */
    public function saveMessage(int $categoryId, string $username, string $messageText, string $createdAt): void
    {
        try {
            $this->insertMessageToDatabase($categoryId, $username, $messageText, $createdAt);

            echo 'Сообщение успешно сохранено!';
        } catch (PDOException $e) {
            throw new DatabaseException('Ошибка при сохранении сообщения: ' . $e->getMessage());
        }
    }

    /**
     * @throws DatabaseException
     */
    public function getMessagesByCategory(int $categoryId): array
    {
        try {
            $sql = "SELECT * FROM messages WHERE category_id = :categoryId ORDER BY created_at DESC";

            $statement = $this->dbConnection->prepare($sql);

            $statement->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException('Ошибка при получении сообщений по категории: ' . $e->getMessage());
        }
    }

    private function insertMessageToDatabase(int $categoryId, string $username, string $messageText): void
    {
        $sql = "INSERT INTO messages (category_id, username, content, created_at) VALUES (:categoryId, :username, :content, CONVERT_TZ(NOW(), 'UTC', 'Asia/Yekaterinburg'))";
        $statement = $this->dbConnection->prepare($sql);

        $statement->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':content', $messageText, PDO::PARAM_STR);

        $statement->execute();
    }
}
