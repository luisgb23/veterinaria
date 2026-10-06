<?php

include_once "includes/timezone.php";
$insert = include_once "includes/conexion.php";
$nombre = $_POST["txtNombre"];
$especie = $_POST["txtEspecie"];
$propietario = $_POST["txtPropietario"];
$fchNac = $_POST["txtFechaNac"];
$fchCreacion = date('Y-m-d H:i:s');
$estado = 1;
$sentencia = $insert->prepare("INSERT INTO mascotas (MascotaNombre,EspecieId, PropietarioId, MascotaFchNac,MascotaFchCreacion,MascotaEstado) VALUES (?, ?, ?, ?, ?, ?)");
$sentencia->bind_param("siissi", $nombre, $especie,$propietario, $fchNac, $fchCreacion ,$estado);
$sentencia->execute();
header("Location: mascotas.php");
?>