<?php
// Development router for the transitional root document directory.
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ?: '/');
$root=dirname(__DIR__);
require_once $root.'/vendor/autoload.php';
$base=App\Http\Url::base();
if($base!=='' && $path!==$base && !str_starts_with($path,$base.'/')) return false;
$path=$base===''?$path:(substr($path,strlen($base)) ?: '/');
$resolved=realpath($root.$path);
$relative=$resolved ? substr($resolved,strlen($root)+1) : ltrim($path,'/');
$first=explode('/',$relative)[0];
if(str_contains($path,"\0") || str_contains($path,'..') || ($resolved && $resolved!==$root && !str_starts_with($resolved,$root.DIRECTORY_SEPARATOR)) || str_starts_with($first,'.') || in_array($first,['storage','archivos','db','database','config','src','views','vendor','tests','includes','templates','conexion.php','composer.json','composer.lock','README.md','test.php','testAgregar.php'])) {
    http_response_code(404); echo 'Página no encontrada'; return true;
}
if($path==='/api/v1' || str_starts_with($path,'/api/v1/')) { require __DIR__.'/api.php'; return true; }
return false;
