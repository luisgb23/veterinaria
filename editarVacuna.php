<?php


include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_POST["txtId"];
$fecha = $_POST["txtFecha"];
$mascota = $_POST["txtMascotaId"];
$fchVencVac = $_POST["txtFchVenc"];
$fchModificacion = date('Y-m-d H:i:s');

$sentencia = $mysqli->prepare("UPDATE vacunas SET
VacunaFchIngreso = ?,
MascotaId = ?,
VacunaFchVenc = ?,
VacunaFchModificacion = ?
WHERE VacunaId = ?");
$sentencia->bind_param("ssssi", $fecha, $mascota, $fchVencVac, $fchModificacion, $id);
$sentencia->execute();
header("Location: vacunas.php");
