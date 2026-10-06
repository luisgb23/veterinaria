<?php
namespace App\Http;
final class View
{
    public static function escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
    public static function url(string $route, array $params = []): string
    {
        return '/public/index.php?' . http_build_query(['route' => $route] + $params);
    }
    public static function render(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);
        $content = dirname(__DIR__, 2) . '/views/' . $template . '.php';
        require dirname(__DIR__, 2) . '/views/layouts/main.php';
    }
    public static function redirect(string $route): never
    {
        header('Location: ' . self::url($route), true, 303);
        exit;
    }
}
