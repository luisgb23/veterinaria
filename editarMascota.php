<?php

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_POST["txtId"];
$nombre = $_POST["txtNombre"];
$especie = $_POST["txtEspecie"];
$propietario = $_POST["txtPropietario"];
$fechaNac = $_POST["txtFechaNac"];
$fechaVac = $_POST["txtFechaVac"];
$fchModificacion = date('Y-m-d H:i:s');

$sentencia = $mysqli->prepare("UPDATE mascotas SET
MascotaNombre = ?,
EspecieId = ?,
PropietarioId = ?,
MascotaFchNac = ?,
MascotaFchVencVac = ?,
MascotaFchModificacion = ?
WHERE MascotaId = ?");
$sentencia->bind_param("ssssssi", $nombre, $especie, $propietario, $fechaNac, $fechaVac, $fchModificacion, $id);
$sentencia->execute();
header("Location: mascotas.php");