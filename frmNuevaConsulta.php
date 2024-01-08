<?php include ("templates/header.php");?>
<div class="card">
    <div class="card-header">
        <h3 class="text-center">Nueva consulta</h3>
    </div>
    <div class="card-body">
        <form action="agregarConsulta.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha de consulta</label>
                        <input type="date" class="form-control" name="txtFechaConsulta" id="fecha">
                    </div>
                </div>
                <div class="col-6">
                    <label for="mascota" class="form-label">Mascota</label>
                    <select class="form-select" name="txtMascotaId" id="mascota">
                        <?php
                        $mysqli = include './includes/conexion.php';
                        $resultado = $mysqli->query("SELECT MascotaId, MascotaNombre, PropietarioNombre, PropietarioApellido FROM mascotas INNER JOIN Propietarios on mascotas.PropietarioId = Propietarios.PropietarioId");
                        while ($mascota = $resultado->fetch_assoc()){
                            echo "<option value=\"$mascota[MascotaId]\">$mascota[MascotaNombre] ($mascota[PropietarioNombre] $mascota[PropietarioApellido])</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <input type="text" class="form-control" name="txtMotivo">
            </div>
            <div class="mb-3">
                <label for="diagnostico" class="form-label">Diagnostico</label>
                <textarea class="form-control" name="txtDiagnostico" id="diagnostico" cols="30" rows="10"></textarea>
            </div>
            <div class="mb-3">
                <label for="tratamiento" class="form-label">Tratamiento</label>
                <textarea class="form-control" name="txtTratamiento" id="tratamiento" cols="30" rows="10"></textarea>
            </div>
            <div class="mb-3">
                <label for="Archivos" class="form-label">Archivos</label>
                <div class="row">
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="archivo1"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control" id="archivo1" name="archivo1">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="archivo2"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control" id="archivo2" name="archivo2">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="archivo3"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control" id="archivo3" name="archivo3">
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success" value="Agregar">
                <a href="consultas.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php include("templates/footer.php"); ?>
