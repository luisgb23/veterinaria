<?php
include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$fecha = $_POST["txtFecha"];
$mascota = $_POST["txtMascotaId"];
$valor = $_POST["txtValor"];
$observacion = $_POST["txtObservacion"];
$fchCreacion = date('Y-m-d H:i:s');
$estado = 1;
$sentencia = $mysqli->prepare("INSERT INTO cuotas
(CuotaFecha, MascotaId, CuotaValor, CuotaObservacion, CuotaFchCreacion, CuotaEstado)
VALUES
(?, ?, ?, ?, ?, ?)");
$sentencia->bind_param("sisssi", $fecha, $mascota, $valor, $observacion, $fchCreacion,$estado);
$sentencia->execute();
header("Location: cuotas.php");
?>