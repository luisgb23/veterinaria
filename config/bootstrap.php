<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
date_default_timezone_set('America/Montevideo');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['cookie_path' => App\Http\Url::base().'/', 'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'cookie_httponly' => true, 'cookie_samesite' => 'Lax']);
}
