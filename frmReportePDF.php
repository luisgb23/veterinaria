<?php
include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM consultas INNER JOIN Mascotas on consultas.MascotaId = Mascotas.MascotaId where ConsultaId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
$resultado = $sentencia->get_result();
$consulta = $resultado->fetch_assoc();
if (!$consulta) {
    exit("No hay resultados para ese ID");
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Veterinaria Itapebí</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1>Veterinaria Itapebi</h1>
        <h3>Resumen de historia clínica</h3>
        <hr>
        <h2>Consulta de <?php echo $consulta['MascotaNombre'];?> el día: <?php echo $consulta['ConsultaFecha'];?></h2>
        <h5>Nombre de paciente:</h5>
        <p><?php echo $consulta['MascotaNombre'];?></p>
        <h5>Motivo de consulta:</h5>
        <p><?php echo $consulta['ConsultaMotivo'];?></p>
        <h5>Diagnostico:</h5>
        <p> <?php echo $consulta['ConsultaDiagnostico'];?></p>
        <h5>Tratamiento:</h5>
        <p><?php echo $consulta['ConsultaTratamiento'];?></p>
</body>
</html>


