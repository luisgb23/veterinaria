<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<?php

$mysqli = include_once "includes/conexion.php";

$user = $_POST['txtUser'];
$pass = $_POST['txtPwd'];

$resultado = $mysqli->query("SELECT UsuarioId, UsuarioNombre, UsuarioUser, UsuarioPwd FROM usuario where UsuarioUser = '$user' and UsuarioPwd = sha1('$pass')");

$reg = $resultado->fetch_assoc();

if(!$reg){
    echo  '<script>alert("Usuario o contraseña incorrectos")</script>';
    sleep(3);
    header('Location: index.php');
    exit;
}else{
    session_start();
    $_SESSION['nombre'] = $reg['UsuarioNombre'];
    header('Location: dashboard.php');
}

