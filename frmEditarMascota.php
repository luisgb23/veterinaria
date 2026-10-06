<?php include ("templates/header.php");

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM mascotas where MascotaId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
$resultado = $sentencia->get_result();
$mascotas = $resultado->fetch_assoc();
if (!$mascotas) {
    exit("No hay resultados para ese ID");
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="text-center">Editar mascota</h3>
    </div>
    <div class="card-body">
        <form action="editarMascota.php" method="post">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" class="form-control" name="txtNombre" value="<?php echo $mascotas['MascotaNombre'];?>" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="fchnac" class="form-label">Fecha de nacimiento</label>
                        <input type="date" id="fchnac" class="form-control" name="txtFechaNac" value="<?php echo $mascotas['MascotaFchNac'];?>" required>
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
                            while ($especies = $resultado->fetch_assoc()) {
                                if ($especies['EspecieId'] == $mascotas['EspecieId'])
                                    echo "<option value=\"$especies[EspecieId]\" selected>$especies[EspecieNombre]</option>";
                                else
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
                            var_dump($mysqli);
                            $resultado = $mysqli->query("SELECT PropietarioId, PropietarioNombre, PropietarioApellido FROM propietarios");
                            while ($propietarios = $resultado->fetch_assoc()){
                                if ($propietarios['PropietarioId'] == $mascotas['PropietarioId'])
                                    echo "<option value=\"$propietarios[PropietarioId]\" selected>$propietarios[PropietarioNombre] $propietarios[PropietarioApellido]</option>";
                                else
                                    echo "<option value=\"$propietarios[PropietarioId]\">$propietarios[PropietarioNombre] $propietarios[PropietarioApellido]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-success" value="Editar">
            <a href="mascotas.php" class="btn btn-danger">Cancelar</a>
            <input type="hidden" name="txtId" value="<?php echo $mascotas['MascotaId'];?>">
        </form>
    </div>
</div>

<?php include ("templates/footer.php");?>
