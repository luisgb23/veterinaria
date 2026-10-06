<?php include ("templates/header.php");
include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM cuotas where CuotaId = ?");
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
        <h3 class="text-center">Editar cuota</h3>
    </div>
    <div class="card-body">
        <form action="editarCuota.php" method="post">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha de consulta</label>
                        <input type="date" class="form-control" name="txtFecha" id="fecha" value="<?php echo $consulta['CuotaFecha'];?>">
                    </div>
                </div>
                <div class="col-6">
                    <label for="mascota" class="form-label">Mascota</label>
                    <select class="form-select" name="txtMascotaId" id="mascota">
                        <?php
                        $mysqli = include './includes/conexion.php';
                        $resultado = $mysqli->query("SELECT MascotaId, MascotaNombre FROM mascotas");
                        while ($mascota = $resultado->fetch_assoc()){
                            if ($mascota['MascotaId'] == $consulta['MascotaId'])
                                echo "<option value=\"$mascota[MascotaId]\">$mascota[MascotaNombre]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <label for="valor" class="form-label">Valor cuota</label>
            <div class="input-group mb-3">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="valor" name="txtValor" value="<?php echo $consulta['CuotaValor'];?>">
                <span class="input-group-text">.00</span>
            </div>
            <div class="mb-3">
                <label for="observacion" class="form-label">Observaciones</label>
                <input type="text" class="form-control" id="observacion" name="txtObservacion" value="<?php echo $consulta['CuotaObservacion'];?>">
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-success" value="Editar">
                <a href="cuotas.php" class="btn btn-danger">Cancelar</a>
                <input type="hidden" name="txtId" value="<?php echo $consulta['CuotaId'];?>">
            </div>
        </form>
    </div>
</div>
<?php include("templates/footer.php"); ?>
