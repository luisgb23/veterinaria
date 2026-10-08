<?php
declare(strict_types=1);
// Binary/JSON responses must not include diagnostic HTML; errors go to the PHP log.
ini_set('display_errors','0'); ini_set('log_errors','1');
try {
    require dirname(__DIR__).'/config/bootstrap.php';
    $path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ?: '';
    $path=preg_replace('#^/api/v1/?#','',$path);
    (new App\Http\ApiRouter())->dispatch($path,$_SERVER['REQUEST_METHOD']);
} catch(Throwable $error) {
    error_log((string)$error);
    App\Http\ApiResponse::error('No se pudo completar la operación.',500);
}
