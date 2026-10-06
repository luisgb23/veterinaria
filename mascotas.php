<?php include ("templates/header.php");

$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM mascotas INNER JOIN  Especies on mascotas.EspecieId = Especies.EspecieId
    INNER JOIN Propietarios on mascotas.PropietarioId = Propietarios.PropietarioId  WHERE MascotaEstado = 1");
$mascotas = $resultado->fetch_all(MYSQLI_ASSOC);

?>
    <div class="card">
        <div class="card-header">
            <div class="cabezal">
                <h3 class="text-center">Mascotas</h3>
                <a href="frmAgregarMascota.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Agregar</a>
            </div>
        </div>
        <div class="card-body">
            <div class="responsive-table">
                <table class="table table-hover" id="myTable">
                    <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Especie</th>
                        <th scope="col">Propietario</th>
                        <th scope="col">Fecha de nacimiento</th>
                        <th scope="col">Edad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($mascotas as $mascota){
                        ?>
                    <tr>
                        <td><?php echo $mascota['MascotaId'];?></td>
                        <td><?php echo $mascota['MascotaNombre'];?></td>
                        <td><?php echo $mascota['EspecieNombre'];?></td>
                        <td><?php echo $mascota['PropietarioNombre']." ".$mascota['PropietarioApellido'];?></td>
                        <td><?php
                            $fecha = $mascota['MascotaFchNac'];
                            $newFecha = date("d/m/Y", strtotime($fecha));
                            echo $newFecha ?>
                        </td>
                        <td>
                            <?php
                               require_once 'helpers/funciones.php';
                               $resultado = obtenerEdad($fecha);
                               echo $resultado;
                            ?>
                        </td>
                        <td>
                            <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-ticket-detailed-fill"></i>Ver consultas</a>
                            <a href="frmEditarMascota.php?id=<?php echo $mascota['MascotaId'];?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                            <a href="eliminarMascota.php?id=<?php echo $mascota['MascotaId'];?>" class="btn btn-danger btn-del"><i class="bi bi-trash3-fill"></i> Eliminar</a>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Consultas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->
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
                    'Eliminado!',
                    'La mascota fue eliminada!',
                    'success'
                )
            }
        })
    })
</script>
<?php include ("templates/footer.php");?>
