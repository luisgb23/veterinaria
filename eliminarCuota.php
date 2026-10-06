<?php

if (!isset($_GET["id"])) {
    exit("No hay id");
}
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("update cuotas set CuotaEstado = 0 where CuotaId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
header("Location: cuotas.php");