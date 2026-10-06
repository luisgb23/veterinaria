<?php

include ("templates/header.php");
include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM vacunas where VacunaId = ?");
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
        <h3 class="text-center">Editar vacuna</h3>
    </div>
    <div class="card-body">
        <form action="editarVacuna.php" method="post">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" id="fecha" class="form-control" name="txtFecha" value="<?php echo $consulta['VacunaFchIngreso'];?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="mb-3">
                        <label for="mascota" class="form-label">Mascota</label>
                        <select class="form-select" name="txtMascotaId" id="mascota">
                            <?php
                            $mysqli = include './includes/conexion.php';
                            $resultado = $mysqli->query("SELECT MascotaId, MascotaNombre, PropietarioNombre, PropietarioApellido FROM mascotas INNER JOIN Propietarios on mascotas.PropietarioId = Propietarios.PropietarioId");
                            while ($mascota = $resultado->fetch_assoc()){
                                if ($mascota['MascotaId'] == $consulta['MascotaId'])
                                echo "<option value=\"$mascota[MascotaId]\">$mascota[MascotaNombre] ($mascota[PropietarioNombre] $mascota[PropietarioApellido])</option>";
                            else
                                echo "<option value=\"$mascota[MascotaId]\">$mascota[MascotaNombre] ($mascota[PropietarioNombre] $mascota[PropietarioApellido])</option>";
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="mb-3">
                        <label for="fechaVenc" class="form-label">Fecha vencimiento</label>
                        <input type="date" id="fechaVenc" class="form-control" name="txtFchVenc" value="<?php echo $consulta['VacunaFchVenc'];?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <input type="submit" class="btn btn-success" value="Editar">
                    <a href="vacunas.php" class="btn btn-danger">Cancelar</a>
                    <input type="hidden" name="txtId" value="<?php echo $consulta['VacunaId'];?>">
                </div>
            </div>
    </div>
    </form>
    </div>
</div>
