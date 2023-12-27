<?php include ("templates/header.php");
$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM mascotas INNER JOIN Propietarios on mascotas.PropietarioId = propietarios.PropietarioId WHERE MascotaEstado = 1 order by MascotaFchVencVac desc");
$mascotas = $resultado->fetch_all(MYSQLI_ASSOC);

?>
<div class="card">
    <div class="card-header">
        <h3 class="text-center">Vencimientos de vacunas por nombre y fecha</h3>
    </div>
    <div class="card-body">
        <div class="responsive-table">
            <table class="table table-hover text-center" id="myTable">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Nombre</th>
                    <th scope="col" class="text-center">Fecha de vencimiento</th>
                    <th scope="col" class="text-center">Nombre de propietario</th>
                    <th scope="col" class="text-center">Teléfono de contacto</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($mascotas as $mascota){
                    ?>
                    <tr>
                        <th><?php echo $mascota['MascotaNombre'];?></th>
                        <th><?php echo $mascota['MascotaFchVencVac'];?></th>
                        <th><?php echo $mascota['PropietarioNombre'];?> <?php echo $mascota['PropietarioApellido'];?></th>
                        <th><?php echo $mascota['PropietarioTelefono'];?></th>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include ("templates/footer.php");?>
