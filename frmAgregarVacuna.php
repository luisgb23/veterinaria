<?php include ("templates/header.php");
?>
<script src="js/funciones.js"></script>
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Agregar vacuna</h3>
        </div>
        <div class="card-body">
            <form action="agregarVacuna.php" method="post">
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" id="fecha" class="form-control" name="txtFecha">
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
                                <input type="date" id="fechaVenc" class="form-control" name="txtFchVenc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <input type="submit" class="btn btn-success" value="Agregar">
                            <a href="vacunas.php" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php include("templates/footer.php"); ?>
