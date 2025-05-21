<?php
namespace App\Models;

use App\Modules\DatabaseConnection;
use App\Exceptions\DatabaseException;
use PDO;
use PDOException;

/**
 * Модель отвечает за взаимодействие с таблицей `categories` в базе данных
 */
class CategoriesModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getConnection();
    }

    /**
     * @throws DatabaseException
     */
    public function getCategories(): array
    {
        try {
            $query = "SELECT id, title FROM categories";
            $statement = $this->pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Ошибка при получении категорий: " . $e->getMessage(), 0, $e);
        }
    }
}