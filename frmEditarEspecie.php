<?php include ("templates/header.php");

include_once "includes/timezone.php";
$mysqli = include_once "includes/conexion.php";
$id = $_GET["id"];
$sentencia = $mysqli->prepare("SELECT * FROM especies where EspecieId = ?");
$sentencia->bind_param("i", $id);
$sentencia->execute();
$resultado = $sentencia->get_result();
$especie = $resultado->fetch_assoc();
if (!$especie) {
    exit("No hay resultados para ese ID");
}
?>
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Editar especie</h3>
        </div>
        <div class="card-body">
            <form action="editarEspecie.php" method="post">
                <div class="mb-3">
                    <label for="descripción" class="form-label">Descripción</label>
                    <input type="text" id="descripción" class="form-control" name="txtNombre" value="<?php echo $especie['EspecieNombre'];?>" required>
                </div>
                <input type="submit" class="btn btn-success" value="Editar">
                <a href="especies.php" class="btn btn-danger">Cancelar</a>
                <input type="hidden" name="txtId" value="<?php echo $especie['EspecieId'];?>">
            </form>
        </div>
    </div>
<?php include ("templates/footer.php");?>