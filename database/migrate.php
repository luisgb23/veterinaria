<?php
// Explicit CLI operation; application startup never changes the schema.
if(PHP_SAPI!=='cli') { http_response_code(404); exit; }
require dirname(__DIR__).'/vendor/autoload.php';
$db=App\Models\Database::connect();
$sql=file_get_contents(__DIR__.'/migrations/001_create_vacunas.sql');
$db->query($sql);
echo "Migración vacunas aplicada (idempotente).\n";
