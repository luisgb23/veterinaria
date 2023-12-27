<?php

include_once "includes/timezone.php";
$insert = include_once "includes/conexion.php";

if ((isset($_FILES['archivo1'])) && !empty($_FILES['archivo1']) && (isset($_FILES['archivo2'])) && !empty($_FILES['archivo2']) && (isset($_FILES['archivo3'])) && !empty($_FILES['archivo3'])) {
    $fecha = $_POST["txtFechaConsulta"];
    $mascota = $_POST["txtMascotaId"];
    $motivo = $_POST["txtMotivo"];
    $diagnostico = $_POST["txtDiagnostico"];
    $tratamiento = $_POST["txtTratamiento"];
    $archivo1 = $_FILES['archivo1']['name'];
    $archivo2 = $_FILES['archivo2']['name'];
    $archivo3 = $_FILES['archivo3']['name'];
    $arvivo1_nombre = $_FILES['archivo1']['name'];
    $archivo1_tmp = $_FILES['archivo1']['tmp_name'];
    $arvivo2_nombre = $_FILES['archivo2']['name'];
    $archivo2_tmp = $_FILES['archivo2']['tmp_name'];
    $arvivo3_nombre = $_FILES['archivo3']['name'];
    $archivo3_tmp = $_FILES['archivo3']['tmp_name'];
    $route1 = "archivos/".$archivo1;
    $route2 = "archivos/".$archivo2;
    $route3 = "archivos/".$archivo3;
    move_uploaded_file($archivo1_tmp,$route1);
    move_uploaded_file($archivo2_tmp,$route2);
    move_uploaded_file($archivo3_tmp,$route3);
    $fchCreacion = date('Y-m-d H:i:s');
    $estado = 1;
    $sentencia = $insert->prepare("INSERT INTO consultas 
(ConsultaFecha, MascotaId, ConsultaMotivo,ConsultaDiagnostico,ConsultaTratamiento, ConsultaArchivo1, ConsultaArchivo2, ConsultaArchivo3, ConsultaFchCreacion, ConsultaEstado) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sentencia->bind_param("sisssssssi", $fecha, $mascota,$motivo, $diagnostico, $tratamiento, $archivo1, $archivo2, $archivo3,$fchCreacion ,$estado);
    $sentencia->execute();
    header("Location: consultas.php");
} elseif ((isset($_FILES['archivo1'])) && !empty($_FILES['archivo1']) && (isset($_FILES['archivo2'])) && !empty($_FILES['archivo2'])){
    $fecha = $_POST["txtFechaConsulta"];
    $mascota = $_POST["txtMascotaId"];
    $motivo = $_POST["txtMotivo"];
    $diagnostico = $_POST["txtDiagnostico"];
    $tratamiento = $_POST["txtTratamiento"];
    $archivo1 = $_FILES['archivo1']['name'];
    $archivo2 = $_FILES['archivo2']['name'];
    $arvivo1_nombre = $_FILES['archivo1']['name'];
    $archivo1_tmp = $_FILES['archivo1']['tmp_name'];
    $arvivo2_nombre = $_FILES['archivo2']['name'];
    $archivo2_tmp = $_FILES['archivo2']['tmp_name'];
    $route1 = "archivos/".$archivo1;
    $route2 = "archivos/".$archivo2;
    move_uploaded_file($archivo1_tmp,$route1);
    move_uploaded_file($archivo2_tmp,$route2);
    $fchCreacion = date('Y-m-d H:i:s');
    $estado = 1;
    $sentencia = $insert->prepare("INSERT INTO consultas 
(ConsultaFecha, MascotaId, ConsultaMotivo,ConsultaDiagnostico,ConsultaTratamiento, ConsultaArchivo1, ConsultaArchivo2, ConsultaFchCreacion, ConsultaEstado) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sentencia->bind_param("sissssssi", $fecha, $mascota,$motivo, $diagnostico, $tratamiento, $archivo1, $archivo2,$fchCreacion ,$estado);
    $sentencia->execute();
    header("Location: consultas.php");
}elseif ((isset($_FILES['archivo1'])) && !empty($_FILES['archivo1'])){
    $fecha = $_POST["txtFechaConsulta"];
    $mascota = $_POST["txtMascotaId"];
    $motivo = $_POST["txtMotivo"];
    $diagnostico = $_POST["txtDiagnostico"];
    $tratamiento = $_POST["txtTratamiento"];
    $archivo1 = $_FILES['archivo1']['name'];
    $arvivo1_nombre = $_FILES['archivo1']['name'];
    $archivo1_tmp = $_FILES['archivo1']['tmp_name'];
    $route1 = "archivos/".$archivo1;
    move_uploaded_file($archivo1_tmp,$route1);
    $fchCreacion = date('Y-m-d H:i:s');
    $estado = 1;
    $sentencia = $insert->prepare("INSERT INTO consultas 
(ConsultaFecha, MascotaId, ConsultaMotivo,ConsultaDiagnostico,ConsultaTratamiento, ConsultaArchivo1, ConsultaFchCreacion, ConsultaEstado) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $sentencia->bind_param("sisssssi", $fecha, $mascota,$motivo, $diagnostico, $tratamiento, $archivo1,$fchCreacion ,$estado);
    $sentencia->execute();
    header("Location: consultas.php");
} else{
    $fecha = $_POST["txtFechaConsulta"];
    $mascota = $_POST["txtMascotaId"];
    $motivo = $_POST["txtMotivo"];
    $diagnostico = $_POST["txtDiagnostico"];
    $tratamiento = $_POST["txtTratamiento"];
    $fchCreacion = date('Y-m-d H:i:s');
    $estado = 1;
    $sentencia = $insert->prepare("INSERT INTO consultas 
(ConsultaFecha, MascotaId, ConsultaMotivo,ConsultaDiagnostico,ConsultaTratamiento, ConsultaFchCreacion, ConsultaEstado) 
VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sentencia->bind_param("sissssi", $fecha, $mascota,$motivo, $diagnostico, $tratamiento, $fchCreacion ,$estado);
    $sentencia->execute();
    header("Location: consultas.php");
}

?>
