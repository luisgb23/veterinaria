<?php include ("templates/header.php");
$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM vacunas INNER JOIN mascotas on vacunas.MascotaId = mascotas.MascotaId inner join propietarios on mascotas.PropietarioId = Propietarios.PropietarioId ORDER BY Vacunas.VacunaFchVenc ASC");
$mascotas = $resultado->fetch_all(MYSQLI_ASSOC);
$hoy = date('Y-m-d');
?>
<div class="card">
    <div class="card-header">
        <h3 class="text-center">Historico de vencimiento de vacunas</h3>
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
                        <td><?php echo $mascota['MascotaNombre'];?></td>
                        <?php
                            $fecha = $mascota['VacunaFchVenc'];
                            $newFecha = date("d/m/Y", strtotime($fecha));
                            if ($fecha < $hoy) {
                                echo '<td>';
                                echo '<span class="badge text-bg-danger">'.$newFecha." ".'(Vencidas)'.'</span>';
                                echo '</td>';
                        }else{
                                 echo '<td>';
                                 echo $newFecha;
                                 echo '</td>';
                            }
                        ?>
                        <td><?php echo $mascota['PropietarioNombre'];?> <?php echo $mascota['PropietarioApellido'];?></td>
                        <td><?php echo $mascota['PropietarioTelefono'];?></td>
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
