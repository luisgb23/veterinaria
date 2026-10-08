<?php
// Development router for the transitional root document directory.
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ?: '/');
$root=dirname(__DIR__);
$resolved=realpath($root.$path);
$relative=$resolved ? substr($resolved,strlen($root)+1) : ltrim($path,'/');
$first=explode('/',$relative)[0];
if(str_contains($path,"\0") || str_contains($path,'..') || ($resolved && !str_starts_with($resolved,$root.DIRECTORY_SEPARATOR)) || str_starts_with($first,'.') || in_array($first,['storage','archivos','db','database','config','src','views','vendor','tests','includes','templates','conexion.php','composer.json','composer.lock','README.md','test.php','testAgregar.php'])) {
    http_response_code(404); echo 'Página no encontrada'; return true;
}
return false;
