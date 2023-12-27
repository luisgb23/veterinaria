<?php include ("templates/header.php");?>
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Agregar propietario</h3>
        </div>
        <div class="card-body">
            <form action="agregarPropietario.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" class="form-control" name="txtNombre" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" class="form-control" name="txtApellido" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" id="telefono" class="form-control" name="txtTelefono" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="txtEmail">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" id="direccion" class="form-control" name="txtDireccion" required>
                </div>
                <input type="submit" class="btn btn-success" value="Agregar">
                <a href="propietarios.php" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
<?php include ("templates/footer.php");?>