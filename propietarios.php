<?php include ("templates/header.php");?>
<?php

$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * from propietarios WHERE PropietarioEstado = 1");
$propietarios = $resultado->fetch_all(MYSQLI_ASSOC);

?>
<div class="card">
    <div class="card-header">
        <div class="cabezal">
            <h3 class="text-center">Propietarios</h3>
            <a href="frmAgregarPropietario.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <div class="responsive-table">
            <table class="table table-hover text-center" id="myTable">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Código</th>
                    <th scope="col" class="text-center">Nombre</th>
                    <th scope="col" class="text-center">Apellido</th>
                    <th scope="col" class="text-center">Teléfono</th>
                    <th scope="col" class="text-center">Dirección</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($propietarios as $propietario) {
                    ?>
                    <tr>
                        <th><?php echo $propietario['PropietarioId'];?></th>
                        <th><?php echo $propietario['PropietarioNombre'];?></th>
                        <th><?php echo $propietario['PropietarioApellido'];?></th>
                        <th><?php echo $propietario['PropietarioTelefono'];?></th>
                        <th><?php echo $propietario['PropietarioDireccion'];?></th>
                        <th><?php echo $propietario['PropietarioEmail'];?></th>
                        <td>
                            <a href="frmEditarPropietario.php?id=<?php echo $propietario['PropietarioId'];?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                            <a href="eliminarPropietario.php?id=<?php echo $propietario['PropietarioId'];?>" class="btn btn-danger btn-del"><i class="bi bi-trash3-fill"></i> Eliminar</a>
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
                    'El propietario fue eliminado!',
                    'success'
                )
            }
        })
    })
</script>
<?php include ("templates/footer.php");?>
