<?php

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_POST["txtId"];
$nombre = $_POST["txtNombre"];
$fchModificacion = date('Y-m-d H:i:s');

$sentencia = $mysqli->prepare("UPDATE especies SET
EspecieNombre = ?,
EspecieFchModificacion = ?
WHERE EspecieId = ?");
$sentencia->bind_param("ssi", $nombre, $fchModificacion, $id);
$sentencia->execute();
header("Location: especies.php");

