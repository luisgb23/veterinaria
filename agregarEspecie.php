<?php
include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$nombre = $_POST["txtNombre"];
$fchCreacion = date('Y-m-d H:i:s');
$estado = 1;
$sentencia = $mysqli->prepare("INSERT INTO especies
(EspecieNombre, EspecieFchCreacion, EspecieEstado)
VALUES
(?, ?, ?)");
$sentencia->bind_param("sss", $nombre, $fchCreacion,$estado);
$sentencia->execute();
header("Location: especies.php");
?>