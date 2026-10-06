<?php

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_POST["txtId"];
$fecha = $_POST["txtFecha"];
$mascota = $_POST["txtMascotaId"];
$valor = $_POST["txtValor"];
$observacion = $_POST["txtObservacion"];
$fchModificacion = date('Y-m-d H:i:s');

$sentencia = $mysqli->prepare("UPDATE cuotas SET
CuotaFecha = ?,
MascotaId = ?,
CuotaValor = ?, 
CuotaObservacion = ?,
CuotaFchModificacion = ?
WHERE CuotaId = ?");
$sentencia->bind_param("sisssi", $fecha, $mascota, $valor, $observacion, $fchModificacion, $id);
$sentencia->execute();
header("Location: cuotas.php");