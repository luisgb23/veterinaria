<?php

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_POST["txtId"];
$nombre = $_POST["txtNombre"];
$apellido = $_POST["txtApellido"];
$telefono = $_POST["txtTelefono"];
$direccion = $_POST["txtDireccion"];
$email = $_POST["txtEmail"];
$fchModificacion = date('Y-m-d H:i:s');

$sentencia = $mysqli->prepare("UPDATE propietarios SET
PropietarioNombre = ?,
PropietarioApellido = ?,
PropietarioTelefono = ?,
PropietarioDireccion = ?,
PropietarioEmail = ?,
PropietarioFchModificacion = ?
WHERE PropietarioId = ?");
$sentencia->bind_param("ssssssi", $nombre, $apellido, $telefono, $direccion, $email, $fchModificacion, $id);
$sentencia->execute();
header("Location: propietarios.php");