<?php include ("templates/header.php");
$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM vacunas INNER JOIN Mascotas on vacunas.MascotaId = mascotas.MascotaId WHERE VacunaEstado = 1");
$vacunas = $resultado->fetch_all(MYSQLI_ASSOC);
?>
    <div class="card">
        <div class="card-header">
            <div class="cabezal">
                <h3 class="text-center">Vencimiento de vacunas</h3>
                <a href="frmAgregarVacuna.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Ingreso de vacunas</a>
            </div>
        </div>
        <div class="card-body">
            <div class="responsive-table">
                <table class="table table-hover text-center" id="myTable">
                    <thead>
                    <tr>
                        <th scope="col" class="text-center">Código</th>
                        <th scope="col" class="text-center">Fecha ingreso</th>
                        <th scope="col" class="text-center">Mascota</th>
                        <th scope="col" class="text-center">Fecha vencimiento</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($vacunas as $vacuna){
                        ?>
                        <tr>
                            <td><?php echo $vacuna['VacunaId'];?></td>
                            <td><?php
                                $fecha = $vacuna['VacunaFchIngreso'];
                                $newFecha = date("d/m/Y", strtotime($fecha));
                                echo $newFecha?></td>
                            <td><?php echo $vacuna['MascotaNombre'];?></td>
                            <td>
                                <?php
                                $fechaVenc = $vacuna['VacunaFchVenc'];
                                $newFchVenc = date("d/m/Y", strtotime($fechaVenc));
                                echo $newFchVenc?>
                            </td>
                            <td>
                                <a href="frmEditarVacuna.php?id=<?php echo $vacuna['VacunaId'];?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                                <a href="eliminarVacuna.php?id=<?php echo $vacuna['VacunaId'];?>" class="btn btn-danger btn-del"><i class="bi bi-trash3-fill"></i> Eliminar</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script>
        $('.btn-del').on('click', function(e){
            e.preventDefault();
            const href = $(this).attr('href')

            Swal.fire({
                title: 'Estas seguro de eliminar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result)=>{
                if(result.value){
                    document.location.href = href;
                    Swal.fire(
                        'Eliminada!',
                        'La vacuna fue eliminada!',
                        'success'
                    )
                }
            })
        })
    </script>
<?php include("templates/footer.php"); ?>