<?php
declare(strict_types=1);
require dirname(__DIR__) . '/config/bootstrap.php';
$route = $mvcRoute ?? ($_GET['route'] ?? 'especies');
try {
    if (!is_string($route)) {
        http_response_code(400);
        echo 'Ruta inválida';
    } else {
        (new App\Http\Router(require dirname(__DIR__) . '/config/routes.php'))->dispatch($route, $_SERVER['REQUEST_METHOD']);
    }
} catch (Throwable $error) {
    error_log((string) $error);
    http_response_code(500);
    echo 'No se pudo completar la operación';
}
