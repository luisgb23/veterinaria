<?php include ("templates/header.php");

$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM consultas INNER JOIN Mascotas on consultas.MascotaId = Mascotas.MascotaId WHERE ConsultaEstado = 1");
$consultas = $resultado->fetch_all(MYSQLI_ASSOC);


?>
    <div class="card">
        <div class="card-header">
            <div class="cabezal">
                <h3 class="text-center">Consultas médicas</h3>
                <a href="frmNuevaConsulta.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nueva consulta</a>
            </div>
        </div>
        <div class="card-body">
            <div class="responsive-table">
                <table class="table table-hover text-center" id="myTable">
                    <thead>
                    <tr>
                        <th scope="col" class="text-center">Código</th>
                        <th scope="col" class="text-center">Fecha</th>
                        <th scope="col" class="text-center">Mascota</th>
                        <th scope="col" class="text-center">Motivo</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($consultas as $consulta){
                    ?>
                    <tr>
                        <td><?php echo $consulta['ConsultaId'];?></td>
                        <td><?php echo $consulta['ConsultaFecha'];?></td>
                        <td><?php echo $consulta['MascotaNombre'];?></td>
                        <td><?php echo $consulta['ConsultaMotivo'];?></td>
                    <td>
                        <a href="frmEditarConsulta.php?id=<?php echo $consulta['ConsultaId'];?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                        <a href="eliminarConsulta.php?id=<?php echo $consulta['ConsultaId'];?>" class="btn btn-danger btn-del"><i class="bi bi-trash3-fill"></i> Eliminar</a>
                        <a href="frmDetalleConsulta.php?id=<?php echo $consulta['ConsultaId'];?>" class="btn btn-light"><i class="bi bi-ticket-detailed-fill"></i> Ver detalle</a>
                        <a href="reportePDF.php?id=<?php echo $consulta['ConsultaId'];?>"  target="_blank" class="btn btn-secondary"><i class="bi bi-filetype-pdf"></i> Imprimir</a>
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
                        'La consulta fue eliminada!',
                        'success'
                    )
                }
            })
        })
    </script>

<?php include ("templates/footer.php");?>