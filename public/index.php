<?php
/**
 * Главный входной файл приложения.
 * В задачи файла входят:
 * 1) Обработка входящего запроса;
 * 2) Определение соответствующего маршрута;
 * 3) Вызов соответствующего контроллера и метода действия.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Models\MessageModel;
use App\Controllers\MessageController;
use App\Modules\DatabaseConnection;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routes = include __DIR__ . '/../router/routes.php';

$routeFound = false;

foreach ($routes as $route => $controllerAction) {
    if ($uri === $route) {
        $routeFound = true;

        [$controllerName, $methodName] = explode('@', $controllerAction);

        $controllerFilePath = __DIR__ . "/../app/Controllers/{$controllerName}.php";

        if (!file_exists($controllerFilePath)) {
            http_response_code(404);
            echo 'Контроллер не найден';
            break;
        }

        require_once $controllerFilePath;

        $controllerClass = "\\App\\Controllers\\{$controllerName}";

        $pdo = DatabaseConnection::getConnection();

        switch ($controllerName) {
            case 'MessageController':
                $model = new MessageModel($pdo);
                $controllerInstance = new MessageController($model);
                break;
            case 'HomeController':
                $controllerInstance = new HomeController($pdo);
                break;
            default:
                http_response_code(500);
                echo 'Неизвестный контроллер';
                exit;
        }

        $params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

        if (method_exists($controllerInstance, $methodName)) {
            call_user_func_array([$controllerInstance, $methodName], array_values($params));
        } else {
            http_response_code(404);
            echo 'Метод не найден';
        }

        break;
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo '404 Страница не найдена';
}