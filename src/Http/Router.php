<?php
namespace App\Http;
final class Router
{
    public function __construct(private array $routes) {}
    public function dispatch(string $route, string $method): void
    {
        if (!isset($this->routes[$route])) {
            http_response_code(404);
            echo 'Página no encontrada';
            return;
        }
        [$expectedMethod, $controller, $action, $authenticated] = $this->routes[$route];
        if ($method !== $expectedMethod) {
            header('Allow: ' . $expectedMethod);
            http_response_code(405);
            echo 'Método no permitido';
            return;
        }
        if ($authenticated) AuthMiddleware::check();
        if ($method === 'POST' && !Csrf::valid()) {
            http_response_code(403);
            echo 'Formulario vencido o inválido';
            return;
        }
        (new $controller())->$action();
    }
}
