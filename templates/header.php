<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location: index.php');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Veterinaria Itapebi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../img/logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
</head>
<body class="fondo">
<header class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../dashboard.php">Veterinaria Itapebí</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <nav class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" href="../especies.php">Especies</a>
                <a class="nav-link" href="../propietarios.php">Propietarios</a>
                <a class="nav-link" href="../mascotas.php">Mascotas</a>
                <a class="nav-link" href="../vacunas.php">Vacunas</a>
                <a class="nav-link" href="../consultas.php">Consultas</a>
                <a class="nav-link" href="../cuotas.php">Cuotas</a>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Listados
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="../vencimientos.php">Vencimiento de vacunas</a></li>
                    </ul>
                </li>
            </div>
        </nav>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="../logout.php" class="btn btn-outline-success" type="submit"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
        </div>
    </div>
</header>
<main class="container mt-4">
    <h3 class="text-white">Bienvenid@ <?php echo $_SESSION['nombre'] ?></h3>