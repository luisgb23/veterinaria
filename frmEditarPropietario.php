<?php include ("templates/header.php");

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM propietarios where PropietarioId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
$resultado = $sentencia->get_result();
$propietario = $resultado->fetch_assoc();
if (!$propietario) {
    exit("No hay resultados para ese ID");
}
?>
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Editar propietario</h3>
        </div>
        <div class="card-body">
            <form action="editarPropietario.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" class="form-control" name="txtNombre" value="<?php echo $propietario['PropietarioNombre'];?>" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" class="form-control" name="txtApellido" value="<?php echo $propietario['PropietarioApellido'];?>"required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" id="telefono" class="form-control" name="txtTelefono" value="<?php echo $propietario['PropietarioTelefono'];?>" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="txtEmail" value="<?php echo $propietario['PropietarioEmail'];?>">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" id="direccion" class="form-control" name="txtDireccion" value="<?php echo $propietario['PropietarioDireccion'];?>"required>
                </div>
                <input type="submit" class="btn btn-success" value="Editar">
                <a href="propietarios.php" class="btn btn-danger">Cancelar</a>
                <input type="hidden" name="txtId" value="<?php echo $propietario['PropietarioId'];?>">
            </form>
        </div>
    </div>
<?php include ("templates/footer.php");?>