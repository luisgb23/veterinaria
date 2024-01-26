<?php include ("templates/header.php");

?>
    <div class="card">
        <div class="card-header">
          <h3 class="text-center">Agregar mascota</h3>
        </div>
        <div class="card-body">
            <form action="agregarMascota.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" class="form-control" name="txtNombre" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="fchnac" class="form-label">Fecha de nacimiento</label>
                            <input type="date" id="fchnac" class="form-control" name="txtFechaNac" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="especie" class="form-label">Especie</label>
                            <select class="form-select" name="txtEspecie">
                                <?php
                                $mysqli = include './includes/conexion.php';
                                $resultado = $mysqli->query("SELECT EspecieId, EspecieNombre FROM especies");
                                while ($especies = $resultado->fetch_assoc()){
                                    echo "<option value=\"$especies[EspecieId]\">$especies[EspecieNombre]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="propietario" class="form-label">Propietario</label>
                            <select class="form-select" name="txtPropietario">
                                <?php
                                $mysqli = include './includes/conexion.php';
                                $resultado = $mysqli->query("SELECT PropietarioId, PropietarioNombre, PropietarioApellido FROM propietarios");
                                while ($propietarios = $resultado->fetch_assoc()){
                                    echo "<option value=\"$propietarios[PropietarioId]\">$propietarios[PropietarioNombre] $propietarios[PropietarioApellido]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success" value="Agregar">
                <a href="mascotas.php" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
<?php include ("templates/footer.php");?>