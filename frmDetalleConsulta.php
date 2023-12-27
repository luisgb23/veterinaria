<?php include ("templates/header.php");
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
<div class="card">
    <div class="card-header">
        <h3 class="text-center">Consulta: <?php echo $consulta['ConsultaId'];?></h3>
    </div>
    <div class="card-body">
        <h5>Fecha de consulta:</h5>
        <p><?php echo $consulta['ConsultaFecha'];?></p>
        <h5>Nombre de mascota:</h5>
        <p><?php echo $consulta['MascotaNombre'];?></p>
        <h5>Motivo:</h5>
        <p><?php echo $consulta['ConsultaMotivo'];?></p>
        <h5>Diagnostico:</h5>
        <p><?php echo $consulta['ConsultaDiagnostico'];?></p>
        <h5>Tratamiento:</h5>
        <p><?php echo $consulta['ConsultaTratamiento'];?></p>
        <a href="consultas.php" class="btn btn-secondary">Volver</a>
    </div>
</div>
<?php include("templates/footer.php"); ?>
