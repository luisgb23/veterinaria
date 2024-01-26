<?php

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_POST["txtId"];
$nombre = $_POST["txtNombre"];
$especie = $_POST["txtEspecie"];
$propietario = $_POST["txtPropietario"];
$fechaNac = $_POST["txtFechaNac"];
$fchModificacion = date('Y-m-d H:i:s');

$sentencia = $mysqli->prepare("UPDATE mascotas SET
MascotaNombre = ?,
EspecieId = ?,
PropietarioId = ?,
MascotaFchNac = ?,
MascotaFchModificacion = ?
WHERE MascotaId = ?");
$sentencia->bind_param("sssssi", $nombre, $especie, $propietario, $fechaNac, $fchModificacion, $id);
$sentencia->execute();
header("Location: mascotas.php");