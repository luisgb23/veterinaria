<?php

if (!isset($_GET["id"])) {
    exit("No hay id");
}
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("update mascotas set MascotaEstado = 0 where MascotaId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
header("Location: mascotas.php");