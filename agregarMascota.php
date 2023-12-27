<?php

include_once "includes/timezone.php";
$insert = include_once "includes/conexion.php";
$nombre = $_POST["txtNombre"];
$especie = $_POST["txtEspecie"];
$propietario = $_POST["txtPropietario"];
$fchNac = $_POST["txtFechaNac"];
$fchVac = $_POST["txtFechaVac"];
$fchCreacion = date('Y-m-d H:i:s');
$estado = 1;
print_r($especie);
print_r($propietario);
$sentencia = $insert->prepare("INSERT INTO mascotas (MascotaNombre,EspecieId, PropietarioId, MascotaFchNac,MascotaFchVencVac,MascotaFchCreacion,MascotaEstado) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sentencia->bind_param("siisssi", $nombre, $especie,$propietario, $fchNac, $fchVac,$fchCreacion ,$estado);
$sentencia->execute();
header("Location: mascotas.php");
?>