<?php include ("templates/header.php");

$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM cuotas INNER JOIN Mascotas on cuotas.MascotaId = Mascotas.MascotaId WHERE CuotaEstado = 1");
$cuotas = $resultado->fetch_all(MYSQLI_ASSOC);

?>
<div class="card">
    <div class="card-header">
        <div class="cabezal">
            <h3 class="text-center">Cuotas</h3>
            <a href="frmNuevaCuota.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nueva cuota</a>
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
                    <th scope="col" class="text-center">Valor cuota</th>
                    <th scope="col" class="text-center">Observaciones</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cuotas as $cuota){
                    ?>
                    <tr>
                        <td><?php echo $cuota['CuotaId'];?></td>
                        <td><?php
                            $fecha = $cuota['CuotaFecha'];
                            $newFecha = date("d/m/Y", strtotime($fecha));
                            echo $newFecha?></td>
                        <td><?php echo $cuota['MascotaNombre'];?></td>
                        <td>$ <?php echo $cuota['CuotaValor'];?></td>
                        <td><?php echo $cuota['CuotaObservacion'];?></td>
                        <td>
                            <a href="frmEditarCuota.php?id=<?php echo $cuota['CuotaId'];?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                            <a href="eliminarCuota.php?id=<?php echo $cuota['CuotaId'];?>" class="btn btn-danger btn-del"><i class="bi bi-trash3-fill"></i> Eliminar</a>
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
                    'La cuota fue eliminada!',
                    'success'
                )
            }
        })
    })
</script>
<?php include ("templates/footer.php");?>
