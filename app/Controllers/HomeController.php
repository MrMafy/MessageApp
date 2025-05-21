<?php

namespace App\Controllers;

use App\Exceptions\DatabaseException;
use App\Models\CategoriesModel;

/**
 * Контроллер главной страницы приложения
 */
class HomeController
{
    private $pdo;
    private CategoriesModel $categoriesModel;

    public function __construct()
    {
        $this->categoriesModel = new CategoriesModel($this->pdo);
    }

    /**
     * @throws DatabaseException
     */
    public function index(): void
    {
        $title = "MessageApp (KK)";
        $categories = $this->categoriesModel->getCategories();

        include __DIR__ . '/../../resources/views/main.php';
    }
}
