<?php
namespace App\Http;
final class Csrf
{
    public static function token(): string
    {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }
    public static function valid(): bool
    {
        $value = $_POST['_token'] ?? null;
        return is_string($value) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $value);
    }
}
