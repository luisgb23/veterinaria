<?php

if (!isset($_GET["id"])) {
    exit("No hay id");
}
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("update vacunas set VacunaEstado = 0 where VacunaId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
header("Location: vacunas.php");