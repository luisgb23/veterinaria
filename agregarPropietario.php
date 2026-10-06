<?php

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$nombre = $_POST["txtNombre"];
$apellido = $_POST["txtApellido"];
$telefono = $_POST["txtTelefono"];
$direccion = $_POST["txtDireccion"];
$email = $_POST["txtEmail"];
$fchCreacion = date('Y-m-d H:i:s');
$estado = 1;
$sentencia = $mysqli->prepare("INSERT INTO propietarios
(PropietarioNombre, PropietarioApellido, PropietarioTelefono, PropietarioDireccion, PropietarioEmail, PropietarioFchCreacion, PropietarioEstado)
VALUES
(?, ?, ?, ?, ?, ?, ?)");
$sentencia->bind_param("sssssss", $nombre, $apellido, $telefono, $direccion, $email, $fchCreacion,$estado);
$sentencia->execute();
header("Location: propietarios.php");
?>