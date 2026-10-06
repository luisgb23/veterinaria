<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MascotaController;

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'mascotas':
        $controller = new MascotaController();
        $controller->index();
        break;
    case 'agregarMascota':
        $controller = new MascotaController();
        $controller->create();
        break;
    default:
        // Por ahora redirigir al index original o mostrar algo
        header("Location: index.php");
        break;
}
