<?php

include_once "includes/timezone.php";
$insert = include_once "includes/conexion.php";
$propietario = $_POST["propietario"];
print_r( $_POST["propietario"]);
$sentencia = $insert->prepare("INSERT INTO prueba (PropietarioId) VALUES (?)");
$sentencia->bind_param("i", $propietario);
$sentencia->execute();
header("Location: dashboard.php");
?>
