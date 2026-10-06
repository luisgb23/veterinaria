<?php
namespace App\Http;
final class AuthMiddleware
{
    public static function check(): void
    {
        if (!isset($_SESSION['nombre'])) {
            View::redirect('login');
        }
    }
}
