<?php

include_once "includes/timezone.php";
$insert = include_once "includes/conexion.php";
$fecha = $_POST["txtFecha"];
$mascota = $_POST["txtMascotaId"];
$fchVencVac = $_POST["txtFchVenc"];
$estado = 1;
$sentencia = $insert->prepare("INSERT INTO vacunas (VacunaFchIngreso,MascotaId, VacunaFchVenc,VacunaEstado) VALUES (?, ?, ?, ?)");
$sentencia->bind_param("sisi", $fecha, $mascota,$fchVencVac, $estado);
$sentencia->execute();
header("Location: vacunas.php");
?>