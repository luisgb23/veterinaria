<?php
namespace App\Http;
final class ApiResponse
{
    public static function json(array $body, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($body,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
        exit;
    }
    public static function error(string $message, int $status, array $errors = []): never
    {
        self::json(['message'=>$message]+($errors?['errors'=>$errors]:[]),$status);
    }
    public static function empty(): never { http_response_code(204); header('Cache-Control: no-store'); exit; }
}
