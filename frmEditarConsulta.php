<?php include ("templates/header.php");

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM consultas where ConsultaId = ?");
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
        <h3 class="text-center">Editar consulta</h3>
    </div>
    <div class="card-body">
        <form action="editarConsulta.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha de consulta</label>
                        <input type="date" class="form-control" name="txtFechaConsulta" id="fecha" value="<?php echo $consulta['ConsultaFecha'];?>">
                    </div>
                </div>
                <div class="col-6">
                    <label for="mascota" class="form-label">Mascota</label>
                    <select class="form-select" name="txtMascotaId" id="mascota">
                        <?php
                        $mysqli = include './includes/conexion.php';
                        $resultado = $mysqli->query("SELECT MascotaId, MascotaNombre,PropietarioNombre, PropietarioApellido FROM mascotas INNER JOIN Propietarios on mascotas.PropietarioId = Propietarios.PropietarioId");
                        while ($mascota = $resultado->fetch_assoc()){
                            if ($mascota['MascotaId'] == $consulta['MascotaId'])
                                echo "<option value=\"$mascota[MascotaId]\" selected>$mascota[MascotaNombre] ($mascota[PropietarioNombre] $mascota[PropietarioApellido])</option>";
                            else
                                echo "<option value=\"$mascota[MascotaId]\">$mascota[MascotaNombre] ($mascota[PropietarioNombre] $mascota[PropietarioApellido]) </option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <input type="text" class="form-control" name="txtMotivo" id="motivo" value="<?php echo $consulta['ConsultaMotivo'];?>">
            </div>
            <div class="mb-3">
                <label for="diagnostico" class="form-label">Diagnostico</label>
                <input class="form-control" name="txtDiagnostico" id="diagnostico" value="<?php echo $consulta['ConsultaDiagnostico'];?>" >
            </div>
            <div class="mb-3">
                <label for="tratamiento" class="form-label">Tratamiento</label>
                <input class="form-control" name="txtTratamiento" id="tratamiento" value="<?php echo $consulta['ConsultaTratamiento'];?>">
            </div>
            <div class="mb-3">
                <label for="Archivos" class="form-label">Archivos</label>
                <div class="row">
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="archivo1"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control" id="archivo1" name="archivo1" value="<?php echo $consulta['ConsultaArchivo1'];?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="archivo2"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control" id="archivo2" name="archivo2" value="<?php echo $consulta['ConsultaArchivo2'];?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="archivo3"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control" id="archivo3" name="archivo3" value="<?php echo $consulta['ConsultaArchivo3'];?>">
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success" value="Editar">
                <a href="consultas.php" class="btn btn-danger">Cancelar</a>
                <input type="hidden" name="txtId" value="<?php echo $consulta['ConsultaId'];?>">
            </div>
        </form>
    </div>
</div>
<?php include("templates/footer.php"); ?>
