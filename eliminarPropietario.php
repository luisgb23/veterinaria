<?php

if (!isset($_GET["id"])) {
    exit("No hay id");
}
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("update propietarios set PropietarioEstado = 0 where PropietarioId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
header("Location: propietarios.php");